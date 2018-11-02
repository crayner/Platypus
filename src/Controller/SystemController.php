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

use App\Entity\AlarmConfirm;
use App\Entity\Person;
use App\Form\AlarmType;
use App\Form\Type\NotificationEventType;
use App\Form\SectionSettingType;
use App\Form\StringReplacementType;
use App\Manager\AlarmManager;
use App\Manager\FlashBagManager;
use App\Manager\MultipleSettingManager;
use App\Manager\NotificationEventManager;
use App\Manager\ScaleManager;
use App\Manager\SettingManager;
use App\Manager\StringReplacementManager;
use App\Manager\ThemeManager;
use App\Manager\VersionManager;
use App\Pagination\NotificationEventPagination;
use App\Pagination\StringReplacementPagination;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Util\ScriptManager;
use Hillrange\Form\Util\UploadFileManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function systemSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager, ScaleManager $scaleManager)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition('System', ['request' => $request]));

        $settings = $request->getSession()->get('manage_settings');

        $multipleSettingManager->setHeader($settings->getSectionsHeader());
        foreach ($settings->getSections() as $name=>$section)
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
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function thirdPartySettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition('ThirdParty', ['request' => $request]));

        $settings = $request->getSession()->get('manage_settings');

        $multipleSettingManager->setHeader($settings->getSectionsHeader());
        foreach ($settings->getSections() as $name=>$section)
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/system/string_replacement/manage/", name="manage_string_replacement")
     * @Security("is_granted('ROLE_ACTION', request)")
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
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/system/string_replacement/{id}/edit/", name="edit_string_replacement")
     * @Security("is_granted('USE_ROUTE', ['manage_string_replacement'])")
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
     * @throws \Exception
     * @Route("/system/string_replacement/{id}/delete/", name="delete_string_replacement")
     * @Security("is_granted('USE_ROUTE', ['manage_string_replacement'])")
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
     * @Security("is_granted('ROLE_ACTION', request)")
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
     * @Security("is_granted('USE_ROUTE', ['manage_notification_events'])")
     */
    public function notificationEventEdit(Request $request, NotificationEventManager $manager, $id = 'Add', $tabName = 'details')
    {
        $entity = $manager->find($id);
dd($entity);
        $form = $this->createForm(NotificationEventType::class, $entity, ['manager' => $manager]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();

            return $this->redirectToRoute('edit_notification_event', ['id' => $entity->getId(), 'tabName' => $tabName]);
        }

        return $this->render('System/notification_event_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
            ]
        );
    }

    /**
     * systemCheck
     *
     * @param VersionManager $manager
     * @param EntityManagerInterface $em
     * @param SettingManager $sm
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/system/check/", name="system_check")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function systemCheck(VersionManager $manager, EntityManagerInterface $em, SettingManager $sm)
    {
        $manager->setEntityManager($em);
        $manager->setSettingManager($sm);
        return $this->render('System/system_check.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }

    /**
     * themesManage
     *
     * @param ThemeManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/system/themes/manage/", name="manage_themes")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function themesManage(ThemeManager $manager)
    {
        return $this->render('System/theme_manage.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }

    /**
     * alarmAction
     *
     * @param SettingManager $sm
     * @param AlarmManager $manager
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/system/alarm/manage/", name="manage_alarm")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function alarmAction(SettingManager $sm, AlarmManager $manager, Request $request, UploadFileManager $fileManager, $id = 'Add')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(AlarmType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if ($form->get('customAlarm')->getData()) {
                $file = $form->get('customAlarm')->getData();

                $sm->set('alarm.custom.name', $file);
            }

            if ($form->get('type')->getData() === 'custom') {
                $file = $sm->get('alarm.custom.name');

                if (empty($file)) {
                    $form->get('type')->addError(new FormError($this->get('translator')->trans('alarm.type.custom.invalid', [], 'System')));
                }
                elseif (! file_exists($sm->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$file))
                {
                    $form->get('type')->addError(new FormError($this->get('translator')->trans('alarm.type.custom.invalid', [], 'System')));
                }
            }

            if ($form->isValid() && $form->get('type')->getData() !== 'none')
            {
                $em = $this->get('doctrine')->getManager();
                $em->persist($entity);
                $em->flush();

                $list = new ArrayCollection($manager->getAlarmConfirmList());

                $person = $em->getRepository(Person::class)->findOneByUser($entity->getUser());

                if ($person instanceof Person)
                    foreach($list as $key=>$value) {
                        if ($person->getId() === $value['id']) {
                            $value['confirmed'] = true;
                            $confirm = new AlarmConfirm();
                            $confirm->setAlarm($entity);
                            $confirm->setPerson($person);
                            $em->persist($confirm);
                            $em->flush();
                        } else
                            $value['confirmed'] = false;
                        $list->set($key, $value);
                    }

                $request->getSession()->set('alarm_confirm_list', $list);
            }
        }
        return $this->render('System/alarm.html.twig',
            [
                'manager' => $manager,
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }
}