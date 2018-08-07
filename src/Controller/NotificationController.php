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

use App\Manager\NotificationManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController
{
    /**
     * notificationManage
     *
     * @param NotificationManager $manager
     * @return JsonResponse
     * @Route("/system/notification/manage/", name="notification_manage")
     */
    public function notificationManage(NotificationManager $manager)
    {
        $content = '
<div class="ticker-wrap">
    <div class="ticker">
        <div class="ticker__item">This is a test message of a notification by Craig.</div>
    </div>
</div>';

        return new JsonResponse(
            [
                'content' => $content,
            ],
            200);
    }
}