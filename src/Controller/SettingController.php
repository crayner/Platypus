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
 * Date: 10/06/2018
 * Time: 12:21
 */
namespace App\Controller;

use App\Entity\AlertLevel;
use App\Entity\FileExtension;
use App\Entity\INDescriptor;
use App\Form\AlertLevelsType;
use App\Form\FileExtensionsType;
use App\Form\FormalAssessmentSettingsType;
use App\Form\INDescriptorsType;
use App\Form\SectionSettingType;
use App\Manager\ExternalAssessmentManager;
use App\Manager\FileExtensionManager;
use App\Manager\FlashBagManager;
use App\Manager\IndividualNeedDescriptorManager;
use App\Manager\MultipleSettingManager;
use App\Manager\SettingManager;
use App\Organism\AlertLevels;
use App\Organism\ExternalAssessments;
use App\Organism\FileExtensions;
use App\Organism\FormalAssessments;
use App\Organism\IndividualNeedsDescriptors;
use App\Repository\SettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * @param         $name
     * @param Request $request
     *
     * @return Response
     * @Route("/setting/{name}/edit/{closeWindow}", name="setting_edit_name")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function editName($name, $closeWindow = null, Request $request, SettingRepository $settingRepository)
    {
        $setting = null;
        $original = $name;
        do {
            $setting = $settingRepository->findOneByName($name);

            if (is_null($setting)) {
                $name = explode('.', $name);
                array_pop($name);
                $name = implode('.', $name);
            }

        } while (is_null($setting) && false !== mb_strpos($name, '.'));

        if (is_null($setting))
            throw new \InvalidArgumentException('The System setting of name: ' . $original . ' was not found');

        return $this->forward(SettingController::class . '::edit', ['id' => $setting->getId(), 'closeWindow' => $closeWindow]);
    }

    /**
     * Delete Setting Image
     * @Route("/setting/{route}/image/{name}/delete/", name="delete_setting_image")
     * @param $name
     * @param $route
     * @param SettingManager $settingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function delete_setting_image($name, $route, SettingManager $settingManager)
    {
        $settingManager->set($name, null);

        return $this->redirectToRoute($route);
    }

    /**
     * manage Multiple Settings
     *
     * @param Request $request
     * @return Response
     * @Route("/setting/manage/multiple/", name="multiple_settings_manage")
     */
    public function manageMultipleSettings(Request $request, MultipleSettingManager $multipleSettingManager, SettingManager $settingManager, FlashBagManager $flashBagManager, ExternalAssessmentManager $eam)
    {
        $settings = $request->getSession()->get('manage_settings');
        foreach ($settings->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($settingManager, $request->request->get('section'));
            return $this->redirectToRoute('manage_settings', ['name' => $settings->getName()]);
        }

        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * Individual Need Settings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @return Response
     * @Route("/setting/individual/need/manage/", name="manage_individual_need_settings")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function individualNeedSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager)
    {
        $descriptors = new IndividualNeedsDescriptors();
        $data = new ArrayCollection($sm->getEntityManager()->getRepository(INDescriptor::class)->findBy([], ['sequence' => 'ASC']));
        $descriptors->setDescriptors($data);
        foreach ($sm->createSettingDefinition('IndividualNeeds')->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);
        $descriptors->setMultipleSettings($multipleSettingManager);

        $form = $this->createForm(INDescriptorsType::class, $descriptors);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('in_descriptors')['multipleSettings']);
            foreach($descriptors->getDescriptors()->toArray() as $entity)
                $sm->getEntityManager()->persist($entity);
            $sm->getEntityManager()->flush();
        }

        return $this->render('Setting/individual_need_settings.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * deleteIndividualNeedDescriptor
     *
     * @Route("/student/individual/need/descriptor/{cid}/delete/", name="remove_individual_need_descriptor")
     * @IsGranted("ROLE_PRINCIPAL")
     * @param $cid
     * @param IndividualNeedDescriptorManager $INDescriptorManager
     * @return JsonResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function deleteStudentNoteCategory($cid, IndividualNeedDescriptorManager $INDescriptorManager)
    {
        $INDescriptorManager->remove($cid);

        return new JsonResponse(
            [
                'message' => $INDescriptorManager->getMessages(),
            ],
            200);
    }

    /**
     * Manage Settings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @return Response
     * @Route("/setting/{name}/manage/", name="manage_settings")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function manageSettings(string $name, Request $request, SettingManager $sm)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition($name));

        return $this->forward(SettingController::class . '::manageMultipleSettings');
    }

    /**
     * Alert Levels Manage
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/setting/alert/level/manage/", name="manage_alert_levels")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function alertLevels(Request $request, EntityManagerInterface $entityManager)
    {
        $entities = $entityManager->getRepository(AlertLevel::class)->findBy([], ['sequence' => 'ASC']);
        $alertLevels = new AlertLevels();
        $alertLevels->setAlertLevels(new ArrayCollection($entities));


        $form = $this->createForm(AlertLevelsType::class, $alertLevels);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($alertLevels->getAlertLevels()->toArray() as $entity)
                $entityManager->persist($entity);
            $entityManager->flush();
        }

        return $this->render('Setting/alert_levels.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * File Extension Manage
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/setting/file/extension/manage/", name="manage_file_extensions")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function fileExtensions(Request $request, EntityManagerInterface $entityManager)
    {
        $entities = $entityManager->getRepository(FileExtension::class)->findBy([], ['extension' => 'ASC']);
        $alertLevels = new FileExtensions();
        $alertLevels->setFileExtensions(new ArrayCollection($entities));


        $form = $this->createForm(FileExtensionsType::class, $alertLevels);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($alertLevels->getFileExtensions()->toArray() as $entity)
                $entityManager->persist($entity);
            $entityManager->flush();
        }

        return $this->render('Setting/file_extensions.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * delete File Extension
     *
     * @Route("/setting/file/extension/{cid}/remove/", name="remove_file_extension")
     * @IsGranted("ROLE_PRINCIPAL")
     * @param $cid
     * @param FileExtensionManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFileExtension($cid, FileExtensionManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->remove($cid);

        $flashBagManager->renderMessages($manager->getMessageManager());

        return $this->redirectToRoute('manage_file_extensions');
    }

    /**
     * Alert Levels Manage
     *
     * @Route("/setting/formal/assessment/manage/", name="manage_formal_assessments")
     * @IsGranted("ROLE_PRINCIPAL")
     * @param Request $request
     * @param ExternalAssessmentManager $externalAssessmentManager
     * @param MultipleSettingManager $multipleSettingManager
     * @return Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function formalAssessments(Request $request, ExternalAssessmentManager $externalAssessmentManager, MultipleSettingManager $multipleSettingManager)
    {
        $settings = new FormalAssessments();
        $results = $externalAssessmentManager->getPrimaryExternalAssessment();

        $settings->setAssessments($results);
        foreach ($externalAssessmentManager->getSettingManager()->createSettingDefinition('FormalAssessment')->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $settings->setMultipleSettings($multipleSettingManager);

        $form = $this->createForm(FormalAssessmentSettingsType::class, $settings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($externalAssessmentManager->getSettingManager(), $request->request->get('formal_assessment_settings'));
            $externalAssessmentManager->setPrimaryExternalAssessment($settings->getAssessments());

        }

        return $this->render('Setting/formal_assessments.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }
}
