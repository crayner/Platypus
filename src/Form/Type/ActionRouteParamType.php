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
 * Date: 10/09/2018
 * Time: 19:02
 */
namespace App\Form\Type;

use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ActionRouteParamType
 * @package App\Form\Type
 */
class ActionRouteParamType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dump($options);
        $builder
            ->add('name', TextType::class,
                ['mapped' => false,]
            )
            ->add('value', TextType::class,
                ['mapped' => false,]
            );
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'action_route_param';
    }
}