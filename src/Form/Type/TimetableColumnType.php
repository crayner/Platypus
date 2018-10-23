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
 * Time: 12:04
 */
namespace App\Form\Type;

use App\Entity\DayOfWeek;
use App\Entity\TimetableColumn;
use Hillrange\Collection\React\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimetableColumnType
 * @package App\Form\Type
 */
class TimetableColumnType extends AbstractType
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
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviated Name',
                ]
            )
            ->add('dayOfWeek', EntityType::class,
                [
                    'class' => DayOfWeek::class,
                    'choice_label' => 'name',
                    'label' => 'Day of the Week'
                ]
            )
            ->add('timetableColumnRows', CollectionType::class,
                [
                    'entry_type' => TimetableColumnRowType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
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
                'translation_domain' => 'Timetable',
                'data_class' => TimetableColumn::class,
            ]
        );
    }
}