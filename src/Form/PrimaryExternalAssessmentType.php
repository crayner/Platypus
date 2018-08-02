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
 * Date: 28/06/2018
 * Time: 17:43
 */
namespace App\Form;

use App\Entity\ExternalAssessment;
use App\Manager\ExternalAssessmentManager;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Type\ChainedChoiceType;
use Hillrange\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrimaryExternalAssessmentType
 * @package App\Form
 */
class PrimaryExternalAssessmentType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * PrimaryExternalAssessmentType constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
            ->add('externalAssessment', EntityType::class,
                [
                    'class' => ExternalAssessment::class,
                    'choice_label' => 'name',
                    'placeholder'           => 'setting.external_assessment.placeholder',
                    'attr'                  => [
                        'class'                 => 'form-control-sm',
                    ],
                    'required'              => false,
                ]
            )
            ->add('fieldSet', ChainedChoiceType::class,
                [
                    'parent_type'           => EntityType::class,
                    'choice_list_class'     => ExternalAssessmentManager::class,
                    'choice_list_method'    => 'getExternalAssessmentFieldChains',
                    'placeholder'           => 'setting.field_set.placeholder',
                    'choice_data_chain'     => 'externalAssessment',
                    'attr'                  => [
                        'class'                 => 'form-control-sm',
                    ],
                    'required'              => false,
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
        return 'primary_external_assessment';
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
                'translation_domain' => 'Setting',
            ]
        );
    }
}