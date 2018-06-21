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
 * Date: 20/06/2018
 * Time: 11:52
 */

namespace App\Form;


use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AttendanceCodeType
 * @package App\Form
 */
class AttendanceCodeType extends AbstractType
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
            ->add('name', TextType::class,
                [
                    'label' => 'attendance_code.name.label',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'attendance_code.name_short.label',
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => 'attendance_code.type.label',
                    'placeholder' => 'attendance_code.type.placeholder',
                ]
            )
            ->add('direction', EnumType::class,
                [
                    'label' => 'attendance_code.direction.label',
                    'placeholder' => 'attendance_code.type.placeholder',
                ]
            )
            ->add('scope', EnumType::class,
                [
                    'label' => 'attendance_code.scope.label',
                    'placeholder' => 'attendance_code.type.placeholder',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'attendance_code.active.label',
                    'div_class' => 'toggleLeft',
                ]
            )
            ->add('reportable', ToggleType::class,
                [
                    'label' => 'attendance_code.reportable.label',
                ]
            )
            ->add('future', ToggleType::class,
                [
                    'label' => 'attendance_code.future.label',
                    'help' => 'attendance_code.future.help',
                ]
            )
            ->add('role', EnumType::class,
                [
                    'label' => 'attendance_code.role.label',
                    'help' => 'attendance_code.role.help',
                    'choice_list_prefix' => 'security_role',
                    'choice_translation_domain' => 'Security'
                ]
            )
            ->add('id', HiddenType::class,
                [
                    'attr' => [
                        'class' => 'removeElement',
                    ],
                ]
            )
            ->add('sequence', HiddenType::class)
        ;
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'attendance_code';
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
                'translation_domain' => 'Student',
            ]
        );
    }
}
