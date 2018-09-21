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
 * Date: 21/09/2018
 * Time: 15:42
 */
namespace App\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class CourseClassSubscriber
 * @package App\Form\Subscriber
 */
class CourseClassSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::PRE_SUBMIT => 'preSubmit',
        );
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

        $data['people'] = array_merge(empty($data['tutors']) ? [] : $data['tutors'], empty($data['students']) ? [] :$data['students'], empty($data['former']) ? [] : $data['former']);


        $people = [];
        foreach($entity->getPeople() as $person)
        {
            $found = false;
            foreach($data['people'] as $q=>$w)
            {
                if ($person->getPerson()->getId() === intval($w['person']))
                {
                    $found = true;
                    $people[] = $w;
                    unset($data['people'][$q]);
                    break;
                }
            }
            if (! $found)
                $entity->removePerson($person);
        }

        $data['people'] = array_merge($people, $data['people']);

        $event->setData($data);
    }
}
