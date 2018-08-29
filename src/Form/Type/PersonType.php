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
 * Date: 24/08/2018
 * Time: 08:56
 */
namespace App\Form\Type;

use App\Entity\Person;
use App\Entity\PersonRole;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\DateType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PersonType
 * @package App\Form\Type
 */
class PersonType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = [];
        $year = date('Y');
        for($i=3; $i<120; $i++)
            $years[] = $year - $i;
        $builder
            ->add('surname', TextType::class,
                [
                    'label' => 'person.surname.label',
                    'help' => 'person.surname.help',
                ]
            )
            ->add('firstName', TextType::class,
                [
                    'label' => 'person.first_name.label',
                    'help' => 'person.first_name.help',
                ]
            )
            ->add('title', EnumType::class,
                [
                    'label' => 'person.title.label',
                    'required' => false,
                ]
            )
            ->add('gender', EnumType::class,
                [
                    'label' => 'person.gender.label',
                    'required' => false,
                ]
            )
            ->add('preferredName', TextType::class,
                [
                    'label' => 'person.preferred_name.label',
                    'help' => 'person.preferred_name.help',
                ]
            )
            ->add('officialName', TextType::class,
                [
                    'label' => 'person.official_name.label',
                    'help' => 'person.official_name.help',
                ]
            )
            ->add('nameInCharacters', TextType::class,
                [
                    'label' => 'person.name_in_characters.label',
                    'help' => 'person.name_in_characters.help',
                ]
            )
            ->add('dob', DateType::class,
                [
                    'label' => 'person.dob.label',
                    'help' => 'person.dob.help',
                    'years' => $years,
                    'required' => false,
                ]
            )
            ->add('photo', ImageType::class,
                [
                    'attr'        => array(
                        'imageClass' => 'headShot75 img-thumbnail',
                    ),
                    'help'       => 'person.photo.help',
                    'label'       => 'person.photo.label',
                    'required'    => false,
                    'deletePhoto' => $options['deletePhoto'],
                    'fileName'    => 'person_' . $options['data']->getId(),
                ]
            )
            ->add('primaryRole', EntityType::class,
                [
                    'help'       => 'person.primary_role.help',
                    'label'       => 'person.primary_role.label',
                    'placeholder'       => 'person.primary_role.placeholder',
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('secondaryRoles', EntityType::class,
                [
                    'help'       => 'person.secondary_roles.help',
                    'label'       => 'person.secondary_roles.label',
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                ]
            )
            ->add('status', EnumType::class,
                [
                    'help'       => 'person.status.help',
                    'label'       => 'person.status.label',
                ]
            )
            ->add('canLogin', ToggleType::class,
                [
                    'help'       => 'person.can_login.help',
                    'label'       => 'person.can_login.label',
                    'mapped' => false,
                ]
            )
            ->add('forcePasswordReset', ToggleType::class,
                [
                    'help'       => 'person.force_password_reset.help',
                    'label'       => 'person.force_password_reset.label',
                    'mapped' => false,
                ]
            )
            ->add('username', TextType::class,
                [
                    'help'       => 'person.username.help',
                    'label'       => 'person.username.label',
                    'mapped' => false,
                ]
            )
            ->add('email', EmailType::class,
                [
                    'label' => 'person.email.label',
                    'required' => false,
                ]
            )
            ->add('emailAlternate', EmailType::class,
                [
                    'label' => 'person.email.label',
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
                'data_class'         => Person::class,
                'translation_domain' => 'Person',
                'attr' => [
                    'noValidate' => true,
                ],
                'allow_extra_fields' => true,
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
        return 'person';
    }

}