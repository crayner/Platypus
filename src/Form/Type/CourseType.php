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
 * Date: 21/09/2018
 * Time: 10:20
 */
namespace App\Form\Type;

use App\Entity\Course;
use App\Entity\CourseClass;
use App\Entity\Department;
use App\Entity\SchoolYear;
use App\Entity\YearGroup;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CourseType
 * @package App\Form\Type
 */
class CourseType extends AbstractType
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
                    'label' => 'Course Name',
                    'help' => 'Must be unique in the school year.'
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Course Short Name',
                ]
            )
            ->add('department', EntityType::class,
                [
                    'label' => 'Learning Area',
                    'class' => Department::class,
                    'placeholder' => 'Please select...'
                ]
            )
            ->add('schoolYear', HiddenEntityType::class,
                [
                    'class' => SchoolYear::class,
                ]
            )
            ->add('description', CKEditorType::class,
                [
                    'label' => 'Description (Blurb)',
                ]
            )
            ->add('map', ToggleType::class,
                [
                    'label' => 'Include In Curriculum Map',
                ]
            )
            ->add('yearGroups', EntityType::class,
                [
                    'label' => 'Year Groups',
                    'help' => 'Enrolment available to selected year groups.',
                    'multiple' => true,
                    'expanded' => true,
                    'choice_label' => 'name',
                    'class' => YearGroup::class,
                    'element_class' => 'text-right',

                ]
            )
            ->add('sequence', IntegerType::class,
                [
                    'label' => 'Order',
                    'help' => 'May be used to adjust arrangement of courses in reports.',
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
                'translation_domain' => 'Course',
                'data_class' => Course::class,
            ]
        );
    }
}