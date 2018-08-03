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
 * Time: 17:33
 */
namespace App\Form;

use App\Entity\Action;
use App\Entity\Module;
use App\Entity\NotificationEvent;
use App\Form\Subscriber\NotificationEventSubscriber;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NotificationEventType
 * @package App\Form
 */
class NotificationEventType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active', ToggleType::class,
                [
                    'label' => 'notification.active.label',
                ]
            )
            ->add('module', HiddenEntityType::class,
                [
                    'label' => 'notification.module.label',
                    'class' => Module::class,
                    'help' => 'notification.module.help',
                ]
            )
            ->add('action', HiddenEntityType::class,
                [
                    'label' => 'notification.action.label',
                    'class' => Action::class,
                    'help' => 'notification.module.help',
                ]
            )
        ;
        $builder->addEventSubscriber(new NotificationEventSubscriber($options['manager']));
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'System',
                'data_class' => NotificationEvent::class,
            ]
        );
        $resolver->setRequired(
            [
                'manager',
            ]
        );
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'notification_event';
    }
}