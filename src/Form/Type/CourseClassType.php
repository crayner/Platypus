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
 * Time: 12:53
 */
namespace App\Form\Type;

use App\Entity\Course;
use App\Entity\CourseClass;
use App\Entity\Scale;
use App\Form\Subscriber\CourseClassSubscriber;
use App\Repository\PersonRepository;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CourseClassType
 * @package App\Form\Type
 */
class CourseClassType extends AbstractType
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
                    'label' => 'Class Name',
                    'help' => 'Displayed as "%{name}"',
                    'help_params' => ['%{name}' => $options['data']->getName()]
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Class Short Name',
                    'help' => 'Displayed as "%{name}"',
                    'help_params' => ['%{name}' => $options['data']->getNameShort()]
                ]
            )
            ->add('useCourseName', ToggleType::class,
                [
                    'label' => 'Use Course Name',
                    'help' => 'Prefix the class name with the course name.'
                ]
            )
            ->add('reportable', ToggleType::class,
                [
                    'label' => 'Reportable?',
                    'help' => 'Should this class show in reports?'
                ]
            )
            ->add('attendance', ToggleType::class,
                [
                    'label' => 'Track Attendance?',
                    'help' => 'Should this class allow attendance to be taken?'
                ]
            )
            ->add('useScale', EntityType::class,
                [
                    'label' => 'Use Scale',
                    'help' => 'Default scale used for this class.',
                    'class' => Scale::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('course', HiddenEntityType::class,
                [
                    'class' => Course::class,
                ]
            )
            ->add('students', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'choices' => $options['choices']['students'],
                        'choice_label' => 'fullName',
                    ],
                ]
            )
            ->add('tutors', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'choices' => $options['choices']['tutors'],
                        'choice_label' => 'fullName',
                    ],
                ]
            )
            ->add('former', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'choices' => $options['choices']['former'],
                        'choice_label' => 'fullName',
                    ],
                ]
            )
            ->add('people', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'entry_options' => [
                        'choices' => [],
                        'choice_label' => 'fullName',
                    ],
                ]
            )
            ->addEventSubscriber(new CourseClassSubscriber())
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
                'data_class' => CourseClass::class,
            ]
        );
        $resolver->setRequired(
            [
                'choices',
            ]
        );
    }
}