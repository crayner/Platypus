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
 * Time: 14:27
 */
namespace App\Form\Type;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Entity\Person;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ClassParticipantType
 * @package App\Form\Type
 */
class ClassParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reportable', ToggleType::class,
                [
                    'button_merge_class' => 'btn-sm',
                    'div_class' => '',
                ]
            )
            ->add('role', EnumType::class,
                [
                    'placeholder' => 'Please select...'
                ]
            )
            ->add('courseClass', HiddenEntityType::class,
                [
                    'class' => CourseClass::class,
                ]
            )
            ->add('person', EntityType::class,
                [
                    'class' => Person::class,
                    'choice_label' => 'fullName',
                    'placeholder' => 'Please select...',
                    'query_builder' => $options['query_builder'],
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
        return 'course_class_participant';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'query_builder',
            ]
        );
        $resolver->setDefaults(
            [
                'translation_domain' => 'Course',
                'data_class' => CourseClassPerson::class,
            ]
        );
    }
}