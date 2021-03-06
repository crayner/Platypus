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
 * Time: 13:14
 */
namespace App\Form\Type;

use App\Entity\TimetableColumn;
use App\Entity\TimetableColumnRow;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\EventSubscriber\ChildParentSubscriber;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\TimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class TimetableColumnRowType
 * @package App\Form\Type
 */
class TimetableColumnRowType extends AbstractType
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
                    'label' => 'Name',
                    'attr' => [
                        'class' => 'form-control-sm',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 12]),
                    ],
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviated Name',
                    'attr' => [
                        'class' => 'form-control-sm',
                    ],
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 4]),
                    ],
                ]
            )
            ->add('timeStart', TimeType::class,
                [
                    'label' => false,
                    'with_seconds' => false,
                ]
            )
            ->add('timeEnd', TimeType::class,
                [
                    'label' => false,
                    'with_seconds' => false,
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('timetableColumn', HiddenEntityType::class,
                [
                    'label' => false,
                    'class' => TimetableColumn::class,
                ]
            )
            ->add('id', HiddenType::class,
                [
                    'attr' => [
                        'class' => 'removeElement',
                    ],
                ]
            )
        ;

        $builder->get('timetableColumn')->addEventSubscriber(new ChildParentSubscriber($options['timetable_column']->getId()));
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'timetable_column',
            ]
        );
        $resolver->setDefaults(
            [
                'translation_domain' => 'Timetable',
                'data_class' => TimetableColumnRow::class,
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
        return 'timetable_column_row';
    }
}