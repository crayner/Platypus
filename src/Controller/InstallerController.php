<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 8/06/2018
 * Time: 14:00
 */
namespace App\Controller;

use App\Demonstration\SchoolFixtures;
use App\Demonstration\SchoolYearFixtures;
use App\Demonstration\TruncateTables;
use App\Demonstration\UserFixtures;
use App\Form\InstallLanguageType;
use App\Form\InstallDatabaseType;
use App\Form\InstallUserType;
use App\Manager\FlashBagManager;
use App\Manager\InstallationManager;
use App\Manager\SettingManager;
use App\Manager\VersionManager;
use App\Organism\Language;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Security\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class InstallerController
 * @package App\Controller
 */
class InstallerController extends Controller
{
    /**
     * start
     * @Route("/installer/start/", name="installer_start")
     * @param InstallationManager $installationManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function start(InstallationManager $installationManager, Request $request)
    {
        $installationManager->setCanInstall();

        $language = new Language();

        $form = $this->createForm(InstallLanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
            if ($installationManager->saveLanguage($request))
                return $this->redirectToRoute('installer_database_settings');
        $content = file_get_contents($installationManager->getProjectDir() . '/.env.dist');
        $content = str_replace(['APP_ENV=dev', 'APP_ENV=prod', 'APP_ENV=test'], "APP_ENV=dev" , $content);
        file_put_contents(__DIR__ . '/../../.env', $content);

        return $this->render('Installer/step1.html.twig',
            [
                'manager' => $installationManager,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * databaseSettings
     * @Route("/installer/database/settings/", name="installer_database_settings")
     * @param InstallationManager $installationManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function databaseSettings(InstallationManager $installationManager, Request $request)
    {
        $installationManager->setStep(1);
        $database = $installationManager->getSQLParameters();

        $form = $this->createForm(InstallDatabaseType::class, $database);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($installationManager->saveSQLParameters($database))
                return $this->redirectToRoute('installer_database_create', ['demo' => $database->isDemoData() ? '1' : '0', 'appEnv' => $database->getEnvironment()]);
        }

        return $this->render('Installer/step2.html.twig',
            [
                'form' => $form->createView(),
                'manager' => $installationManager,
            ]
        );
    }

    /**
     * createDatabase
     *
     * @param InstallationManager $installationManager
     * @param Request $request
     * @param KernelInterface $kernel
     * @param EntityManagerInterface $entityManager
     * @param SettingManager $settingManager
     * @param string $appEnv
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     * @Route("/installer/database/create/{appEnv}/", name="installer_database_create")
     */
    public function createDatabase(InstallationManager $installationManager,
                                   Request $request, KernelInterface $kernel,
                                   EntityManagerInterface $entityManager, SettingManager $settingManager, $appEnv = 'ignore')
    {
        $installationManager->setStep(2);

        if (!$installationManager->hasDatabase(false)) {
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
                'command' => 'doctrine:database:create',
                // (optional) define the value of command arguments
                '--if-not-exists' => '--if-not-exists',
                // (optional) pass options to the command
                '--quiet' => '--quiet',
            ));

            // You can use NullOutput() if you don't need the output
            $output = new BufferedOutput();
            $application->run($input, $output);

            // return the output, don't use if you used NullOutput()
            $output->fetch();

            $installationManager->loadRequiredData();
            return $this->redirectToRoute('installer_system_user');

        }
        if ($installationManager->hasDatabase()) {
            if ($appEnv !== 'ignore') {
                $content = file_get_contents($installationManager->getProjectDir() . '/.env.dist');
                $content = str_replace(['APP_ENV=dev', 'APP_ENV=prod', 'APP_ENV=test'], "APP_ENV=" . $appEnv, $content);
                file_put_contents(__DIR__ . '/../../.env', $content);
            }
            if ($installationManager->setAction(true)->buildDatabase($entityManager)) {
                $settingManager->setAction(true)->buildSystemSettings();
                $installationManager->loadRequiredData();
            }
            $user = $entityManager->getRepository(User::class)->find(1);

            if ($user instanceof UserInterface)
                return $this->redirectToRoute('installer_complete');
        }
        return $this->redirectToRoute('installer_system_user');
    }

    /**
     * createSystemUser
     *
     * @param InstallationManager $installationManager
     * @param Request $request
     * @param SettingManager $settingManager
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/installer/system/user/", name="installer_system_user")
     */
    public function createSystemUser(InstallationManager $installationManager, Request $request, SettingManager $settingManager)
    {
        $installationManager->setStep(2);

        $form = $this->createForm(InstallUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $installationManager->writeSystemUser($request, $settingManager->getEntityManager(), $settingManager);
            return $this->redirectToRoute('installer_complete');
        }

        return $this->render('Installer/step3.html.twig',
            [
                'form' => $form->createView(),
                'manager' => $installationManager,
            ]
        );
    }

    /**
     * loadDummyData
     * @Route("/load/demonstration/{section}/data/", name="load_demonstration_data")
     * @param ObjectManager $objectManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function loadDemonstrationData(ObjectManager $objectManager, Request $request, string $section)
    {
        $logger = $this->get('monolog.logger.demonstration');
        if ($request->hasSession())
            $request->getSession()->invalidate();

        if ($section === 'Start') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new TruncateTables();
            $load->execute($objectManager);
            $logger->addInfo('The existing data has been deleted.');

            $load = new UserFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));

            return $this->redirectToRoute('load_demonstration_data', ['section' => 'School']);
        }

        if ($section === 'School') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new SchoolFixtures();
            $load->load($objectManager, $logger);

            $load = new SchoolYearFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));
            return $this->redirectToRoute('installer_complete');
            return $this->redirectToRoute('load_demonstration_data', ['section' => 'People']);
        }

        if ($section === 'People') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new PeopleFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));
            return $this->redirectToRoute('load_demonstration_data', ['section' => 'Timetable']);
        }

        if ($section === 'Timetable') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new TimetableFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));
            return $this->redirectToRoute('load_demonstration_data', ['section' => 'Activity']);
        }

        if ($section === 'Activity') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new ActivityFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));
            return $this->redirectToRoute('load_demonstration_data', ['section' => 'ActivityStudent']);
        }

        if ($section === 'ActivityStudent') {
            $logger->addInfo(sprintf('Section %s started.', $section));

            $load = new ActivityStudentFixtures();
            $load->load($objectManager, $logger);

            $logger->addInfo(sprintf('Section %s completed.', $section));
            $logger->addInfo('The Dummy Data Load finished.');
            return $this->redirectToRoute('installer_complete');
        }

        die('Data Load Failed.');
    }

    /**
     * installComplete
     *
     * @Route("/installer/complete/", name="installer_complete")
     * @param InstallationManager $installationManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function installComplete(InstallationManager $installationManager)
    {
        $installationManager
            ->setStep(3)
            ->clearStatus()
            ->addStatus('info', 'installer.complete', ['useRaw' => true, '%home%' => $this->generateUrl('home'), 'fixedMessage' => true, '%demo%' => $this->generateUrl('load_demonstration_data', ['section' => 'Start'])]);

        $installationManager->getSettingManager()->set('version', VersionManager::VERSION);

        return $this->render('Installer/step4.html.twig',
            [
                'manager' => $installationManager,
            ]
        );
    }

    /**
     * blank
     *
     * @param array $options
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/blank/", name="blank")
     */
    public function blank(array $options = [])
    {
        return $this->render('blank.html.twig', ['options' => $options]);
    }

    /**
     * install Update
     *
     * @Route("/installer/update/", name="installer_update")
     * @param InstallationManager $installationManager
     * @param SettingManager $settingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function updateInstallation(InstallationManager $installationManager, SettingManager $settingManager, FlashBagManager $flashBagManager)
    {
        if ($installationManager->setAction(true)->buildDatabase($settingManager->getEntityManager())) {
            $settingManager->setAction(true)->buildSystemSettings();
        }

        $installationManager->getMessageManager()->addStatusMessages($installationManager->getStatus(), 'Installer');

        $flashBagManager->renderMessages($installationManager->getMessageManager());

        return $this->forward(InstallerController::class.'::installComplete');
    }
}