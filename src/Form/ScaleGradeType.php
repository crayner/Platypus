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
 * Date: 27/06/2018
 * Time: 09:33
 */
namespace App\Form;

use App\Entity\Scale;
use App\Entity\ScaleGrade;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScaleGradeType
 * @package App\Form
 */
class ScaleGradeType extends AbstractType
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
                'data_class' => ScaleGrade::class,
                'translation_domain' => 'School',
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
            ->add('value', TextType::class,
                [
                    'label' => 'scale_grade.value.label',
                ]
            )
            ->add('descriptor', TextType::class,
                [
                    'label' => 'scale_grade.descriptor.label',
                ]
            )
            ->add('default', ToggleType::class,
                [
                    'label' => 'scale_grade.default.label',
                    'button_merge_class' => 'btn-sm',
                    'div_class' => '',
                    'button_class_on' => 'btn btn-success far fa-thumbs-up',
                    'button_class_off' => 'btn btn-danger far fa-thumbs-down',
                ]
            )
            ->add('sequence', HiddenType::class)
            ->add('id', HiddenType::class,
                [
                    'attr' => [
                        'class' => 'removeElement',
                    ],
                ]
            )
            ->add('scale', HiddenEntityType::class,
                [
                    'class' => Scale::class,
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
        return 'scale_grade';
    }
}