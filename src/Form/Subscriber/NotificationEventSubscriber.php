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
 * Date: 3/08/2018
 * Time: 12:31
 */
namespace App\Form\Subscriber;

use App\Manager\NotificationEventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class NotificationEventSubscriber
 * @package App\Form\Subscriber
 */
class NotificationEventSubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_submit
        // event and that the preSubmit method should be called.
        return [
            FormEvents::PRE_SET_DATA => 'buildForm',
        ];
    }

    /**
     * buildForm
     *
     * @param FormEvent $event
     */
    public function buildForm(FormEvent $event)
    {


/*
        ->add('notificationListeners', CollectionType::class,
        [
            'label' => 'notification.notification_listeners.label',
            'allow_add' => true,
            'allow_delete' => true,
            'entry_type' => NotificationListenerType::class,
        ]
    */


    }

    /**
     * @var NotificationEventManager
     */
    private $manager;

    /**
     * NotificationEventSubscriber constructor.
     * @param NotificationEventManager $manager
     */
    public function __construct(NotificationEventManager $manager)
    {
        $this->manager = $manager;
    }
}