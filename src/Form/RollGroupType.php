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
 * Date: 23/06/2018
 * Time: 09:08
 */
namespace App\Form;

use App\Entity\Facility;
use App\Entity\Person;
use App\Entity\RollGroup;
use App\Entity\SchoolYear;
use App\Util\SchoolYearHelper;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\CollectionEntityType;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RollGroupType
 * @package App\Form
 */
class RollGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (empty($options['data']->getId()))
            $add = true;

        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'roll_group.name.label',
                    'help' => 'roll_group.name.help',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'roll_group.name_short.label',
                    'help' => 'roll_group.name_short.help',
                ]
            )
            ->add('website', UrlType::class,
                [
                    'label' => 'roll_group.website.label',
                    'help' => 'roll_group.website.help',
                    'required' => false,
                ]
            )
            ->add('facility', EntityType::class,
                [
                    'label' => 'roll_group.facility.label',
                    'placeholder' => 'roll_group.facility.placeholder',
                    'class' => Facility::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('attendance', ToggleType::class,
                [
                    'label' => 'roll_group.attendance.label',
                    'help' => 'roll_group.attendance.help',
                ]
            )
            ->add('tutors', CollectionType::class,
                [
                    'help' => 'roll_group.tutors.help',
                    'label' => 'roll_group.tutors.label',
                    'entry_type' => CollectionEntityType::class,
                    'entry_options' => [
                        'block_prefix' => 'roll_group_tutors',
                        'class' => Person::class,
                        'placeholder' => 'empty',
                        'required'  => false,
                        'choice_label' => 'fullName',
                        // @todo need to limit the list of people.
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->select('p, s')
                                ->orderBy('p.surname', 'ASC')
                                ->leftJoin('p.staff', 's')
                                ->addOrderBy('p.firstName', 'ASC')
                                ;
                        },
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('assistants', CollectionType::class,
                [
                    'help' => 'roll_group.assistants.help',
                    'label' => 'roll_group.assistants.label',
                    'entry_type' => CollectionEntityType::class,
                    'entry_options' => [
                        'block_prefix' => 'roll_group_tutors',
                        'class' => Person::class,
                        'placeholder' => 'empty',
                        'required'  => false,
                        'choice_label' => 'fullName',
                        // @todo need to limit the list of people.
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->select('p, s')
                                ->orderBy('p.surname', 'ASC')
                                ->leftJoin('p.staff', 's')
                                ->addOrderBy('p.firstName', 'ASC')
                                ;
                        },
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('nextRollGroup', EntityType::class,
                [
                    'class' => RollGroup::class,
                    'choice_label' => 'name',
                    'label' => 'roll_group.next_roll_group.label',
                    'help' => 'roll_group.next_roll_group.help',
                    'placeholder' => 'empty',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                            ->where('r.schoolYear = :schoolYear')
                            ->setParameter('schoolYear', SchoolYearHelper::getNextSchoolYear())
                            ->orderBy('r.name', 'ASC');
                    },
                ]
            )
            ->add('schoolYear', HiddenEntityType::class,
                [
                    'class' => SchoolYear::class,
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
                'data_class'         => RollGroup::class,
                'translation_domain' => 'School',
                'attr' => [
                    'novalidate' => '',
                    'id' => 'saveForm',
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'roll_group';
    }
}