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
 * Date: 20/09/2018
 * Time: 17:05
 */
namespace App\Form\Type;

use App\Entity\Person;
use App\Entity\RollGroup;
use App\Entity\StudentEnrolment;
use App\Entity\YearGroup;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StudentEnrolmentType
 * @package App\Form\Type
 */
class StudentEnrolmentType extends AbstractType
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
            ->add('student', HiddenEntityType::class,
                [
                    'class' => Person::class,
                ]
            )
            ->add('yearGroup', EntityType::class,
                [
                    'class' => YearGroup::class,
                    'label' => 'Year Group',
                    'choice_label' => 'name',
                    'placeholder' => 'Please Select...',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.sequence')
                        ;
                    },
                ]
            )
            ->add('rollGroup', EntityType::class,
                [
                    'label' => 'Roll Group',
                    'class' => RollGroup::class,
                    'placeholder' => 'Please Select...',
                    'choice_label' => 'name',
                    'query_builder' => function(EntityRepository $er){
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.name')
                            ;
                    },
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
                'data_class' => StudentEnrolment::class,
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
        return 'student_enrolment';
    }
}