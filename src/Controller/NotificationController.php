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

use App\Manager\AlarmManager;
use App\Manager\NotificationManager;
use App\Manager\StaffManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package App\Controller
 */
class NotificationController
{
    /**
     * notificationManage
     *
     * @param Request $request
     * @param NotificationManager $manager
     * @return JsonResponse
     * @Route("/system/notification/manage/", name="notification_manage", methods={"POST"})
     */
    public function notificationManage(NotificationManager $manager)
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
    public function alarmCheck(AlarmManager $manager)    {

        $alarm = $manager->findCurrent();

        return new JsonResponse(
            [
                'alarm' => $alarm->normaliser(),
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
    public function alarmClose(AlarmManager $manager, StaffManager $staffManager)    {

        $alarm = $manager->findCurrent();
        $alarm->setStatus('past');
        $alarm->setTimestampEnd(new \DateTime());

        $manager->saveEntity();

        return new JsonResponse(
            [
                'alarm' => $alarm->normaliser(),
                'staff' => $staffManager->getStaffList()
            ],
            200);
    }
}