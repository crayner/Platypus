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
 * Date: 14/06/2018
 * Time: 16:48
 */
namespace App\Form;

use App\Entity\House;
use App\Form\Subscriber\HouseSubscriber;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HouseType
 * @package App\Form
 */
class HouseType extends AbstractType
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
                    'label'       => 'school.house.name.label',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label'       => 'school.house.name_short.label',
                ]
            )
            ->add('logo', ImageType::class,
                [
                    'label'       => 'school.house.logo.label',
                    'help'       => 'school.house.logo.help',
                    'attr'        => [
                        'imageClass' => 'smallLogo',
                    ],
                    'required'    => false,
                    'deletePhoto' => $options['deletePhoto'],
                    'fileName'    => 'house_logo',
                ]
            )
            ->add('id', HiddenType::class,
                [
                    'attr'        => [
                        'class' => 'removeElement',
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
                'translation_domain' => 'School',
                'data_class'         => House::class,
            ]
        );
        $resolver->setRequired(
            [
                'deletePhoto',
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
        return 'house';
    }

    /**
     * buildView
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['deletePhoto'] = $options['deletePhoto'];
    }
}