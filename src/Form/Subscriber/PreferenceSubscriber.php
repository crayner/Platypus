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
 * Date: 16/08/2018
 * Time: 10:23
 */
namespace App\Form\Subscriber;

use App\Util\PersonHelper;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class PreferenceSubscriber
 * @package App\Form\Subscriber
 */
class PreferenceSubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }

    /**
     * preSetData
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        PersonHelper::setPerson($event->getData());

        if (PersonHelper::hasStaff()) {
            $form = $event->getForm();
            $form->add('smartWorkflowHelp', ToggleType::class,
                [
                    'label' => 'person.smart_workflow_help.label',
                    'data' => PersonHelper::getStaff()->isSmartWorkflowHelp(),
                    'mapped' => false,
                ]
            );
        }

    }
}