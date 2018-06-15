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
 * Date: 14/06/2018
 * Time: 16:52
 */
namespace App\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class HouseSubscriber
 * @package App\Form\Subscriber
 */
class HousesSubscriber implements EventSubscriberInterface
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
        $data   = $event->getData();
        $entity = $event->getForm()->getData();

        $houses = [];
        foreach($entity->getHouses()->toArray() as $q=>$w) {
            foreach ($data['houses'] as $e=>$house)
                if ($house['name'] === $w->getName())
                {
                    $houses[$q] = $house;
                    unset($data['houses'][$e]);
                }
        }
        foreach($data['houses'] as $house)
            $houses[] = $house;

        $data['houses'] = $houses;

        $event->setData($data);
        $event->getForm()->setData($entity);
    }
}