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
 * Date: 14/09/2018
 * Time: 17:30
 */
namespace App\Form\Type;

use App\Entity\Family;
use App\Form\Subscriber\FamilySubscriber;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FamilyType
 * @package App\Form\Type
 */
class FamilyType extends AbstractType
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
                    'label' => 'family.name.label',
                ]
            )
            ->add('nameAddress', TextType::class,
                [
                    'label' => 'family.name_address.label',
                    'help' => 'family.name_address.help',
                ]
            )
            ->add('status', EnumType::class,
                [
                    'label' => 'family.status.label',
                ]
            )
            ->add('adultMembers', CollectionType::class, [
                    'label'        => 'family.adult_members.label',
                    'entry_type'   => FamilyAdultType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'help'         => 'family.adult_members.help',
                    'required'     => true,
                    'button_merge_class' => 'btn-sm fa-fw',
                    'sort_manage' => true,
                ]
            )
            ->add('childMembers', CollectionType::class, [
                    'label'        => 'family.child_members.label',
                    'entry_type'   => FamilyChildType::class,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'help'  => 'family.child_members.help',
                    'button_merge_class' => 'btn-sm fa-fw',
                    'required'     => false,
                ]
            )
            ->add('languageHomePrimary', LanguageType::class,
                [
                    'label'       => 'family.language_home_primary.label',
                    'placeholder' => 'family.language_home_primary.placeholder.e',
                    'required'    => false,
                    'preferred_choices' => ['en','yue','zh'],
                ]
            )
            ->add('languageHomeSecondary', LanguageType::class,
                [
                    'label'       => 'family.language_home_secondary.label',
                    'placeholder' => 'family.language_home_secondary.placeholder',
                    'required'    => false,
                    'preferred_choices' => ['en','yue','zh'],
                ]
            )
        ;
        $builder->addEventSubscriber(new FamilySubscriber($options['manager']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Family::class,
                'translation_domain' => 'Person',
            ]
        );
        $resolver->setRequired(['manager']);
    }
}