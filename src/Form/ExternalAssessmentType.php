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
 * Date: 29/06/2018
 * Time: 11:59
 */
namespace App\Form;

use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentField;
use Hillrange\Form\Type\CollectionEntityType;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalAssessmentType
 * @package App\Form
 */
class ExternalAssessmentType extends AbstractType
{
    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ExternalAssessment::class,
                'translation_domain' => 'School',
                'attr' => [
                    'novalidate' => true,
                ],
            ]
        );
    }

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
                    'label' => 'external_assessment.name.label',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'external_assessment.name_short.label',
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'external_assessment.description.label',
                    'help' => 'external_assessment.description.help',
                    'attr' => [
                        'rows' => 3,
                    ],
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'external_assessment.active.label',
                ]
            )
            ->add('allowFileUpload', ToggleType::class,
                [
                    'label' => 'external_assessment.allow_file_upload.label',
                    'help' => 'external_assessment.allow_file_upload.help',
                ]
            )
            ->add('website', UrlType::class,
                [
                    'label' => 'external_assessment.website.label',
                    'required' => false,
                ]
            )
            ->add('fields', CollectionType::class,
                [
                    'entry_type' => ExternalAssessmentFieldListType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'redirect_route' => 'external_assessment_field_delete',
                    'sort_manage' => true,
                    'button_merge_class' => 'btn-sm',
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
        return 'external_assessment';
    }
}