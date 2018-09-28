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
 * Date: 25/09/2018
 * Time: 14:29
 */
namespace App\Form\Type;

use App\Entity\SchoolYear;
use App\Entity\Timetable;
use App\Entity\YearGroup;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TimetableType
 * @package App\Form\Type
 */
class TimetableType extends AbstractType
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
                    'help' => 'Must be unique within the school year',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Abbreviated Name',
                    'help' => 'Must be unique within the school year',
                ]
            )
            ->add('nameShortDisplay', EnumType::class,
                [
                    'label' => 'Day Column Display Format',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'Active',
                ]
            )
            ->add('yearGroups', EntityType::class,
                [
                    'multiple' => true,
                    'expanded' => true,
                    'choice_label' => 'name',
                    'attr' => [
                        'class' => 'text-right',
                    ],
                    'class' => YearGroup::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.sequence', 'ASC')
                        ;
                    },
                    'label' => 'Year Groups',
                    'help' => 'Select only groups not in an active timetable in this school year.'
                ]
            )
            ->add('schoolYear', HiddenEntityType::class,
                [
                    'class' => SchoolYear::class,
                ]
            )
            ->add('timetableDays', CollectionType::class,
                [
                    'entry_type' => TimetableDayType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'button_merge_class' => 'btn-sm',
                    'remove_element_route' => 'delete_timetable_day',
                    'sort_manage' => true,
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
                'data_class' => Timetable::class,
            ]
        );
    }
}