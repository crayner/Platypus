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

use App\Form\InstallLanguageType;
use App\Form\InstallDatabaseType;
use App\Manager\InstallationManager;
use App\Organism\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
                return $this->redirectToRoute('installer_database_create', ['data' => $database->isDemoData()]);
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
     * @Route("/installer/database/{data}/create/", name="installer_database_create")
     * @param bool $data
     */
    public function createDatabase(bool $data, InstallationManager $installationManager, Request $request)
    {
        $installationManager->setStep(2);
        die('Step 2');
    }
}