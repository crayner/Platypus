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
 * Date: 2/08/2018
 * Time: 12:45
 */
namespace App\Form;

use App\Entity\StringReplacement;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StringReplacementType
 * @package App\Form
 */
class StringReplacementType extends AbstractType
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
            ->add('original', TextType::class,
                [
                    'label' => 'string.original.label',
                ]
            )
            ->add('replacement', TextType::class,
                [
                    'label' => 'string.replacement.label',
                ]
            )
            ->add('replaceMode', EnumType::class,
                [
                    'label' => 'string.mode.label',
                ]
            )
            ->add('caseSensitive', ToggleType::class,
                [
                    'label' => 'string.case_sensitive.label',
                ]
            )
            ->add('priority', IntegerType::class,
                [
                    'label' => 'string.priority.label',
                    'help' => 'string.priority.help',
                    'attr' => [
                        'min' => 0,
                        'max' => 99,
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
                'translation_domain' => 'System',
                'data_class' => StringReplacement::class,
            ]
        );
    }
    public function getBlockPrefix()
    {
        return 'string_replacement';
    }
}