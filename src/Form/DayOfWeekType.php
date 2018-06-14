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
 * Time: 12:14
 */
namespace App\Form;

use App\Entity\DayOfWeek;
use Hillrange\Form\Type\TimeType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DayOfWeekType
 * @package App\Form
 */
class DayOfWeekType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoolDay', ToggleType::class,
                [
                    'label' => 'day_of_week.school_day.label',
                    'button_merge_class' => 'btn-sm',
                    'attr' => [
                        'class' => 'schoolDayChange',
                    ],
                ]
            )
            ->add('schoolOpen', TimeType::class,
                [
                    'label' => 'day_of_week.school_open.label',
                ]
            )
            ->add('schoolStart', TimeType::class,
                [
                    'label' => 'day_of_week.school_start.label',
                ]
            )
            ->add('schoolEnd', TimeType::class,
                [
                    'label' => 'day_of_week.school_end.label',
                ]
            )
            ->add('schoolClose', TimeType::class,
                [
                    'label' => 'day_of_week.school_close.label',
                ]
            )
        ;
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'school_day_of_week';
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
                'data_class' => DayOfWeek::class,
                'translation_domain' => 'School',
                'constraints' => [
                    new \App\Validator\DayOfWeek(),
                ],
            ]
        );
    }
}