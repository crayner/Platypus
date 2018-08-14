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
 * Date: 4/08/2018
 * Time: 11:07
 */
namespace App\Controller;

use App\Entity\AlarmConfirm;
use App\Entity\Person;
use App\Manager\AlarmConfirmManager;
use App\Manager\AlarmManager;
use App\Manager\NotificationManager;
use App\Manager\SettingManager;
use App\Manager\StaffManager;
use App\Util\UserHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class NotificationController
 * @package App\Controller
 */
class NotificationController extends Controller
{
    /**
     * notificationManage
     *
     * @param Request $request
     * @param NotificationManager $manager
     * @return JsonResponse
     * @Route("/system/notification/manage/", name="notification_manage", methods={"POST"})
     */
    public function notificationManage( NotificationManager $manager)
    {
        $id = 1;
        $content =
        [
            [
                'id' => $id++,
                'text' => ' This is a first test message of a notification by Craig.',
                'link' => '',
                'alert' => 'dark',
            ],
            [
                'id' => $id++,
                'text' => 'This is a second test message of a notification by Craig with link.',
                'link' => 'http://www.craigrayner.com',
                'alert' => 'warning',
            ],
            [
                'id' => $id++,
                'text' => 'This is a third test message of a notification by Craig with link.',
                'link' => 'http://www.craigrayner.com',
                'alert' => 'info',
            ],
            [
                'id' => $id++,
                'text' => 'This is a fourth test message of a notification by Craig with link.',
                'link' => '',
            ],
        ];


        return new JsonResponse(
            [
                'content' => [],
            ],
            200);
    }

    /**
     * alarmCheck
     *
     * @param AlarmManager $manager
     * @return JsonResponse
     * @Route("/system/alarm/check/", name="check_alarm", methods={"GET"})
     */
    public function alarmCheck(AlarmManager $manager, SettingManager $settingManager, StaffManager $staffManager, Request $request, AuthorizationCheckerInterface $checker)    {

        $alarm = $manager->findCurrent();

        $alarm->setCustomFile($settingManager->get('alarm.custom.name'));

        $staffList = new ArrayCollection();

        if ($alarm->getId() > 0) {
            $session = $request->getSession();

            if ($session->has('alarm_confirm_list'))
                $staffList = $session->get('alarm_confirm_list');
        }

        $user_permission = $checker->isGranted('ROLE_PRINCIPAL');

        $person = $this->get('doctrine')->getManager()->getRepository(Person::class)->findOneByUser(UserHelper::getCurrentUser());

        if ($person instanceof Person)
            foreach($staffList as $value)
                if ($person->getId() === $value['id'])
                {
                    $person = $value;
                    break;
                }


        return new JsonResponse(
            [
                'alarm' => $alarm->normaliser(),
                'staffList' => $staffList->toArray() ?: [],
                'permission' => $user_permission,
                'currentPerson' => $person ?: [],
            ],
            200);
    }

    /**
     * alarmCheck
     *
     * @param AlarmManager $manager
     * @param StaffManager $staffManager
     * @return JsonResponse
     * @Route("/system/alarm/close/", name="close_alarm", methods={"GET"})
     */
    public function alarmClose(AlarmManager $manager, Request $request)    {

        $alarm = $manager->findCurrent();
        $alarm->setStatus('past');
        $alarm->setTimestampEnd(new \DateTime());

        $session = $request->getSession();

        $manager->saveEntity();

        if( $session->has('alarm_confirm_list'))
            $session->remove('alarm_confirm_list');

        return new JsonResponse(
            [
                'alarm' => $alarm->normaliser(),
                'staffList' => [],
            ],
            200);
    }

    /**
     * alarmCheck
     *
     * @param AlarmManager $manager
     * @param StaffManager $staffManager
     * @return JsonResponse
     * @Route("/system/alarm/{id}/acknowledge/", name="acknowledge_alarm", methods={"GET"})
     */
    public function alarmAcknowledge($id, AlarmManager $manager, AlarmConfirmManager $confirm, Request $request)    {

        $alarm = $manager->findCurrent();
        $person = $confirm->getRepository(Person::class)->find($id);

        $confirm->findOneByAlarmPerson($alarm, $person);

        if ($alarm instanceof Alarm && $person instanceof Person)
            $confirm->saveEntity();

        return new JsonResponse([],
            200);
    }
}