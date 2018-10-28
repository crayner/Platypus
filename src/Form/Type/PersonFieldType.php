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
 * Date: 9/09/2018
 * Time: 16:10
 */
namespace App\Form\Type;

use App\Entity\PersonField;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PersonFieldType
 * @package App\Form\Type
 */
class PersonFieldType extends AbstractType
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
            ->add('fieldList', EntityType::class,
                [
                    'mapped' => false,
                    'help' => 'Select a custom field name to edit that custom field.',
                    'class' => PersonField::class,
                    'choice_label' => 'name',
                    'placeholder' => 'field.list.placeholder',
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                            ->orderBy('f.name')
                            ;
                    },
                    'attr' => [
                        'class' => 'form-control-sm',
                        'onChange' => [
                            'url' => '/person/custom/field/{id}/manage/',
                            'url_options' => [
                                '{id}' => 'value'
                            ],
                            'url_type' => 'redirect',
                        ],
                    ],
                    'data' => $options['data']->getId() ?: 'Add',
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'field.description.label',
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'field.name.label',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'field.active.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('required', ToggleType::class,
                [
                    'label' => 'field.required.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => 'field.type.label',
                    'placeholder' => 'field.type.placeholder',
                ]
            )
            ->add('forStudent', ToggleType::class,
                [
                    'label' => 'field.for_student.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('forStaff', ToggleType::class,
                [
                    'button_merge_class' => 'btn-sm',
                    'label' => 'field.for_staff.label',
                ]
            )
            ->add('forParent', ToggleType::class,
                [
                    'button_merge_class' => 'btn-sm',
                    'label' => 'field.for_parent.label',
                ]
            )
            ->add('forOther', ToggleType::class,
                [
                    'button_merge_class' => 'btn-sm',
                    'label' => 'field.for_other.label',
                ]
            )
            ->add('forDataUpdater', ToggleType::class,
                [
                    'label' => 'field.for_data_updater.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('forApplicationForm', ToggleType::class,
                [
                    'label' => 'field.for_application_form.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('forPublicRegistration', ToggleType::class,
                [
                    'label' => 'field.for_public_registration.label',
                    'button_merge_class' => 'btn-sm',
                ]
            )
        ;
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
                'data_class' => PersonField::class,
                'translation_domain' => 'Person',
            ]
        );
    }
}