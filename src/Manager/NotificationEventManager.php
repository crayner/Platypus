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
 * Date: 2/08/2018
 * Time: 17:13
 */
namespace App\Manager;

use App\Entity\NotificationEvent;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\Yaml\Yaml;

class NotificationEventManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = NotificationEvent::class;

    /**
     * getTabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return Yaml::parse("
details:
    label: notification_event.details.tab
    include: System/notification_event_details.html.twig
    message: notificationEventDetailsMessage
    translation: System
listeners:
    label: notification_event.listener.tab
    include: System/notification_event_listener_list.html.twig
    message: otificationEventListenersMessage
    translation: System
    display: hasDetails
");
    }

    /**
     * hasDetails
     *
     * @return bool
     */
    public function hasDetails()
    {
        if (empty($this->getEntity()->getId()))
            return false;
        return true;
    }
}