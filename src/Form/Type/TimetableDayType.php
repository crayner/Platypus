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
 * Date: 26/09/2018
 * Time: 09:16
 */
namespace App\Form\Type;

use App\Entity\Timetable;
use App\Entity\TimetableColumn;
use App\Entity\TimetableDay;
use Hillrange\Form\Type\ColourType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimetableDayType
 * @package App\Form\Type
 */
class TimetableDayType extends AbstractType
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
                    'label' => false,
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => false,
                ]
            )
            ->add('timetableColumn', EntityType::class,
                [
                    'label' => false,
                    'class' => TimetableColumn::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('colour', ColourType::class,
                [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('fontColour', ColourType::class,
                [
                    'label' => false,
                    'required' => false,
                ]
            )
            ->add('id', HiddenType::class,
                [
                    'attr' => [
                        'class' => 'removeElement',
                    ],
                ]
            )
            ->add('timetable', HiddenEntityType::class,
                [
                    'class' => Timetable::class,
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
                'translation_domain' => 'Timetable',
                'data_class' => TimetableDay::class,
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
        return 'timetable_day';
    }
}