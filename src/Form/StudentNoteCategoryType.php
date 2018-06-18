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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StudentNoteCategoryType
 * @package App\Form
 */
class StudentNoteCategoryType extends AbstractType
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
                    'label' => 'student_note_category.name.label',
                ]
            )
            ->add('template', CKEditorType::class,
                [
                    'label' => 'student_note_category.template.label',
                    'help' => 'student_note_category.template.help',
                    'config_name' => 'setting_toolbar',
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'student_note_category.active.label',
                    'div_class' => '',
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
        return 'student_note_category';
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
                'translation_domain' => 'Student',
            ]
        );
    }
}