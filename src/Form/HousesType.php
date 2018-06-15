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
 * Time: 16:41
 */
namespace App\Form;

use App\Form\Subscriber\HousesSubscriber;
use App\Manager\HouseManager;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class HousesType
 * @package App\Form
 */
class HousesType extends AbstractType
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
            ->add('houses', CollectionType::class,
                [
                    'entry_type'        => HouseType::class,
                    'allow_add'         => true,
                    'allow_delete'      => true,
                    'entry_options'     => [
                        'deletePhoto'       => $options['deletePhoto'],
                    ],
                    'redirect_route'    => 'house_remove',
                    'button_merge_class' => 'btn-sm',
                ]
            )
        ;
        $builder->addEventSubscriber(new HousesSubscriber());
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'School',
            'data_class'         => HouseManager::class,
        ));
        $resolver->setRequired([
            'deletePhoto',
        ]);
    }

    /**
     * getBlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'houses_manage';
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