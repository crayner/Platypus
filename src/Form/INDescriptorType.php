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
 * Date: 19/06/2018
 * Time: 06:46
 */
namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class INDescriptorType
 * @package App\Form
 */
class INDescriptorType extends AbstractType
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
                    'attr' => [
                        'placeholder' => 'individual_need_descriptor.description.name.label',
                        'class' => 'form-control-sm'
                    ],
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'attr' => [
                        'placeholder' => 'individual_need_descriptor.description.name_short.label',
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'required' => false,
                    'attr' => [
                        'rows' => 3,
                        'class' => 'form-control-sm'
                    ],
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
        ;
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'individual_need_descriptor';
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
                'translation_domain' => 'System',
            ]
        );
    }
}