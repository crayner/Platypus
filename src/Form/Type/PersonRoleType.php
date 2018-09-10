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
 * Time: 09:49
 */
namespace App\Form\Type;

use App\Entity\PersonRole;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PersonRoleType
 * @package App\Form\Type
 */
class PersonRoleType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $core = $options['data']->getType() === 'core' ? true : false;
        $help = $core ? 'role.value.set' : null ;
        $builder
            ->add('roleList', EntityType::class,
                [
                    'mapped' => false,
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                    'placeholder' => 'person.roles.placeholder',
                    'required' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('f')
                            ->orderBy('f.name')
                            ;
                    },
                    'attr' => [
                        'onchange' => 'loadPersonRole()',
                    ],
                ]
            )
            ->add('name', TextType::class,
                [
                    'label' => 'role.name.label',
                    'disabled' => $core,
                    'help' => $help,
                ]
            )
            ->add('category', EnumType::class,
                [
                    'label' => 'role.category.label',
                    'placeholder' => 'form.placeholder',
                    'disabled' => $core,
                    'help' => $help,
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'label' => 'role.description.label',
                    'attr' => [
                        'rows' => 5,
                    ],
                    'disabled' => $core,
                    'help' => $help,
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'role.name_short.label',
                    'disabled' => $core,
                    'help' => $help,
                ]
            )
            ->add('type', EnumType::class,
                [
                    'label' => 'role.type.label',
                    'disabled' => $core,
                    'help' => $help,
                ]
            )
            ->add('futureYearsLogin', ToggleType::class,
                [
                    'label' => 'role.future_years_login.label',
                ]
            )
            ->add('pastYearsLogin', ToggleType::class,
                [
                    'label' => 'role.past_years_login.label',
                ]
            )
        ;
        $core = ($core && $options['data']->getRestriction() === 'admin_only') ? true : false;
        $builder->add('restriction', EnumType::class,
                [
                    'label' => 'role.restriction.label',
                    'disabled' => $core,
                    'help' => 'role.restriction.help'.$help,
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
                'translation_domain' => 'Person',
                'data_class' => PersonRole::class,
            ]
        );
    }

}