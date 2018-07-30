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
 * Date: 30/07/2018
 * Time: 09:23
 */
namespace App\Form;

use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentCategory;
use App\Entity\Scale;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalAssessmentCategoryType extends AbstractType
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
            ->add('sequence', HiddenType::class)
            ->add('id', HiddenType::class,
                [
                    'attr' => [
                        'class' => 'removeElement',
                    ],
                ]
            )
            ->add('category', TextType::class,
                [
                    'label' => 'external.assessment.category.label',
                    'help' => 'external.assessment.category.help',
                    'attr' => [
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('externalAssessment', HiddenEntityType::class,
                [
                    'class' => ExternalAssessment::class,
                ]
            )
            ->add('scale', EntityType::class,
                [
                    'class' => Scale::class,
                    'label' => 'external.assessment.scale.label',
                    'placeholder' => 'external.assessment.scale.placeholder',
                    'attr' => [
                        'class' => 'form-control-sm',
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
                'data_class' => ExternalAssessmentCategory::class,
                'translation_domain' => 'School',
                'error_bubbling' => true,
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
        return 'external_assessment_category';
    }
}