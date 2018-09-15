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
 * Date: 15/09/2018
 * Time: 13:21
 */
namespace App\Form\Subscriber;

use App\Form\Type\FamilyRelationshipType;
use App\Manager\FamilyManager;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class FamilySubscriber
 * @package App\Form\Subscriber
 */
class FamilySubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(
            FormEvents::PRE_SET_DATA   => 'preSetData',
        );
    }

    /**
     * preSetData
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        if ($this->manager->hasRelationships())
        {
            $form->add('relationships', CollectionType::class, [
                    'entry_type'   => FamilyRelationshipType::class,
                    'allow_add'    => false,
                    'allow_delete' => false,
                    'button_merge_class' => 'btn-sm',
                    'required'     => false,
                ]
            );
        }
    }

    /**
     * @var FamilyManager
     */
    private $manager;

    /**
     * FamilySubscriber constructor.
     * @param FamilyManager $manager
     */
    public function __construct(FamilyManager $manager)
    {
        $this->manager = $manager;
    }
}