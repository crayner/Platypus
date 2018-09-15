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
 * Date: 15/09/2018
 * Time: 12:37
 */
namespace App\Form\Type;

use App\Entity\Family;
use App\Entity\FamilyRelationship;
use App\Entity\Person;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FamilyRelationshipType
 * @package App\Form\Type
 */
class FamilyRelationshipType extends AbstractType
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
            ->add('relationship', EnumType::class,
                [
                    'placeholder' => 'Required Select...',
                ]
            )
            ->add('child', HiddenEntityType::class,
                [
                    'class' => Person::class,
                ]
            )
            ->add('adult', HiddenEntityType::class,
                [
                    'class' => Person::class,
                ]
            )
            ->add('family', HiddenEntityType::class,
                [
                    'class' => Family::class,
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
                'data_class' => FamilyRelationship::class,
                'translation_domain' => 'Person',
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
        return 'family_relationship';
    }
}