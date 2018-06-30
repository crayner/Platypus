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
 * Time: 12:06
 */
namespace App\Form;

use App\Entity\ExternalAssessmentCategory;
use App\Entity\ExternalAssessmentField;
use App\Entity\YearGroup;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExternalAssessmentFieldType
 * @package App\Form
 */
class ExternalAssessmentFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'external_assessment_field.name.label',
                ]
            )
            ->add('yearGroups', EntityType::class,
                [
                    'label' => 'external_assessment_field.year_groups.label',
                    'help' => 'external_assessment_field.year_groups.help',
                    'multiple' => true,
                    'expanded' => true,
                    'class' => YearGroup::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('y')
                            ->orderBy('y.sequence')
                            ;
                    },
                ]
            )
            ->add('externalAssessmentCategory', EntityType::class,
                [
                    'label' => 'external_assessment_field.external_assessment_category.label',
                    'help' => 'external_assessment_field.external_assessment_category.help',
                    'class' => ExternalAssessmentCategory::class,
                    'choice_label' => 'scaleCategory',
                    'query_builder' => function (EntityRepository $er) use ($data) {
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.sequence')
                            ->where('a.externalAssessment = :ea')
                            ->setParameter('ea', $data->getExternalAssessment())
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
                'data_class' => ExternalAssessmentField::class,
                'translation_domain' => 'School',
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
        return 'external_assessment_field';
    }
}