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
 * Date: 08/10/2018
 * Time: 14:50
 */
namespace App\Form\Type;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Manager\CourseClassManager;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ClassListType
 * @package App\Form\Type
 */
class ClassListType extends AbstractType
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
            ->add('person', HiddenEntityType::class,
                [
                    'class' => Person::class,
                ]
            )
            ->add('reportable', ToggleType::class,
                [
                    'button_merge_class' => 'btn-sm',
                    'div_class' => 'toggleCentre',
                ]
            )
            ->add('courseClass', EntityType::class,
                [
                    'class' => CourseClass::class,
                    'label' => 'Class',
                    'choice_label' => 'name',
                    'choice_value' => 'id',
                    'placeholder' => 'Current Year Group Classes',
                    'choices' => $this->getCourseClassManager()->getStudentCourseClassList($options['student']),
                    'preferred_choices' => $this->getCourseClassManager()->getPreferredStudentCourseClassList(),
                ]
            )
            ->add('role', HiddenType::class,
                [
                    'data' => 'student'
                ]
            )
            ->add('id', HiddenEntityType::class,
                [
                    'class' => CourseClassPerson::class,
                    'attr' => [
                        'class' => 'removeElement',
                    ],
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
                'translation_domain' => 'Person',
                'data_class' => CourseClassPerson::class,
            ]
        );
        $resolver->setRequired([
            'student',
        ]);
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'course_class_person';
    }

    /**
     * @var CourseClassManager
     */
    private $courseClassManager;

    /**
     * ClassListType constructor.
     * @param CourseClassManager $courseClassManager
     */
    public function __construct(CourseClassManager $courseClassManager)
    {
        $this->courseClassManager = $courseClassManager;
    }

    /**
     * @return CourseClassManager
     */
    public function getCourseClassManager(): CourseClassManager
    {
        return $this->courseClassManager;
    }
}