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
 * Date: 31/07/2018
 * Time: 14:15
 */
namespace App\Controller;

use App\Form\NotificationEventType;
use App\Form\SectionSettingType;
use App\Form\StringReplacementType;
use App\Manager\FlashBagManager;
use App\Manager\MultipleSettingManager;
use App\Manager\NotificationEventManager;
use App\Manager\ScaleManager;
use App\Manager\SettingManager;
use App\Manager\StringReplacementManager;
use App\Pagination\NotificationEventPagination;
use App\Pagination\StringReplacementPagination;
use Hillrange\Form\Util\ScriptManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SystemController
 * @package App\Controller
 */
class SystemController extends Controller
{
    /**
     * systemSettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/system/settings/manage/", name="manage_system_settings")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function systemSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager, ScaleManager $scaleManager)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition('System', ['request' => $request]));

        $settings = $request->getSession()->get('manage_settings');
        foreach ($settings->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('section'));
            return $this->redirectToRoute('manage_system_settings');
        }

        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * thirdPartySettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/system/third_party/settings/", name="third_party_settings")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function thirdPartySettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition('ThirdParty', ['request' => $request]));

        $settings = $request->getSession()->get('manage_settings');

        foreach ($settings->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('section'));
            return $this->redirectToRoute('third_party_settings');
        }

        ScriptManager::addScript('Setting/third_party_email.html.twig');

        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * stringReplacementManage
     *
     * @param Request $request
     * @param StringReplacementPagination $pagination
     * @param StringReplacementManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/system/string_replacement/manage/", name="manage_string_replacement")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function stringReplacementManage(Request $request, StringReplacementPagination $pagination, StringReplacementManager $manager)
    {
        $pagination->injectRequest($request);

        $pagination->getDataSet();

        return $this->render('System/string_replacement_manage.html.twig',
            [
                'pagination' => $pagination,
                'manager' => $manager,
            ]
        );
    }

    /**
     * stringReplacementEdit
     *
     * @param Request $request
     * @param StringReplacementManager $manager
     * @param mixed $id
     * @Route("/system/string_replacement/{id}/edit/", name="edit_string_replacement")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function stringReplacementEdit(Request $request, StringReplacementManager $manager, $id = 'Add')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(StringReplacementType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->get('doctrine')->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->clear('stringReplacement');

            if ($id === 'Add')
                $this->redirectToRoute('edit_string_replacement', ['id' => $entity->getId()]);
        }

        return $this->render('System/string_replacement_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * stringReplacementDelete
     *
     * @param StringReplacementManager $manager
     * @param int $id
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/system/string_replacement/{id}/delete/", name="delete_string_replacement")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function stringReplacementDelete(StringReplacementManager $manager, int $id, FlashBagManager $flashBagManager)
    {
        $manager->delete($id);

        $flashBagManager->renderMessages($manager->getMessageManager());

        $this->get('session')->clear('stringReplacement');

        return $this->redirectToRoute('manage_string_replacement');
    }

    /**
     * notificationEventManage
     *
     * @param Request $request
     * @param StringReplacementPagination $pagination
     * @param StringReplacementManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/system/notification_events/manage/", name="manage_notification_events")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function notificationEventManage(Request $request, NotificationEventPagination $pagination, StringReplacementManager $manager)
    {
        $pagination->injectRequest($request);

        $pagination->getDataSet();

        return $this->render('System/notification_event_manage.html.twig',
            [
                'pagination' => $pagination,
                'manager' => $manager,
            ]
        );
   }

    /**
     * notificationEventEdit
     *
     * @param Request $request
     * @param NotificationEventManager $manager
     * @param int $id
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/system/notification_event/{id}/edit/", name="edit_notification_event")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function notificationEventEdit(Request $request, NotificationEventManager $manager, int $id, $tabName = 'details')
    {
        $scale = $manager->find($id);

        $form = $this->createForm(NotificationEventType::class, $scale, ['manager' => $manager]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($scale);
            $manager->getEntityManager()->flush();

            return $this->redirectToRoute('edit_notification_event', ['id' => $scale->getId(), 'tabName' => $tabName]);
        }

        return $this->render('System/notification_event_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
            ]
        );
    }
}