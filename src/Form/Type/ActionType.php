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
 * Time: 16:22
 */
namespace App\Form\Type;

use App\Entity\Action;
use App\Entity\PersonRole;
use Doctrine\Common\Collections\ArrayCollection;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ActionType
 * @package App\Form\Type
 */
class ActionType extends AbstractType
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
                    'label' => 'action.name.label',
                ]
            )
            ->add('groupBy', EnumType::class,
                [
                    'label' => 'action.groupBy.label',
                    'placeholder' => 'action.groupBy.placeholder',
                ]
            )
            ->add('route', TextType::class,
                [
                    'label' => 'action.route.label',
                    'help' => 'action.route.help',
                ]
            )
            ->add('routeParams', CollectionType::class,
                [
                    'label' => 'action.route_params.label',
                    'help' => 'action.route_params.help',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => ActionRouteParamType::class,
                    'button_merge_class' => 'btn-sm',
                    'data' => new ArrayCollection($options['data']->getRouteParams())
                ]
            )
            ->add('role', EntityType::class,
                [
                    'label' => 'action.role.label',
                    'help' => 'action.role.help',
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                    'placeholder' => 'action.role.placeholder',
                ]
            )
            ->add('personRoles', EntityType::class,
                [
                    'label' => 'action.person_roles.label',
                    'help' => 'action.person_roles.help',
                    'multiple' => true,
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                    'required' => false,
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
                'translation_domain' => 'Security',
                'data_class' => Action::class,
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
        return 'action_permission';
    }
}