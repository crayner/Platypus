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
 * Date: 28/08/2018
 * Time: 11:49
 */
namespace App\Form\Type;

use App\Entity\Address;
use Hillrange\Form\Type\AutoCompleteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressChoiceType
 * @package App\Form\Type
 */
class AddressChoiceType extends AbstractType
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
            ->add('address', AutoCompleteType::class,
                [
                    'class'        => Address::class,
                    'choice_label' => 'singleLineAddress',
                    'empty_data'   => null,
                    'required'     => false,
                    'label'        => 'select.address.label',
                    'help'          => 'select.address.help',
                    'mapped'        => false,
                    'attr'          => [
                        'class' => 'addressList',
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
                'translation_domain' => 'Address',
                'data_class' => Address::class,
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
        return 'address_choice';
    }

}