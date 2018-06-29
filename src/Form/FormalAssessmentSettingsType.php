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
 * Time: 17:41
 */
namespace App\Form;

use App\Organism\FormalAssessments;
use App\Organism\PrimaryExternalAssessment;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormalAssessmentSettingsType
 * @package App\Form
 */
class FormalAssessmentSettingsType extends AbstractType
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
            ->add('assessments', CollectionType::class,
                [
                    'entry_type' => PrimaryExternalAssessmentType::class,
                    'entry_options' => [
                        'data_class' => PrimaryExternalAssessment::class,
                    ],
                ]
            )
            ->add('multipleSettings', SectionSettingType::class,
                [
                    'data' => $options['data']->getMultipleSettings(),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => FormalAssessments::class,
                'translation_domain' => 'Setting',
            ]
        );
    }
}
