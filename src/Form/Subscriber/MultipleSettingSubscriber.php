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
 * Date: 4/10/2018
 * Time: 13:46
 */
namespace App\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class MultipleSettingSubscriber
 * @package App\Form\Subscriber
 */
class MultipleSettingSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * preSubmit
     *
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $entity = $event->getForm()->getData();
        foreach($entity->getCollection()->getIterator() as $q=>$setting)
            if (empty($data['collection'][$q]) && $setting->getSetting()->getSettingType() === 'multiEnum')
            {
                $data['collection'][$q] = ['value' => []];
                $event->setData($data);
            }
    }
}