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
 * Time: 09:18
 */
namespace App\Form\Type;

use App\Entity\FamilyMemberAdult;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FamilyAdultType
 * @package App\Form\Type
 */
class FamilyAdultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', EntityType::class,
                [
                    'class' => Person::class,
                    'choice_label' => 'fullName',
                    'placeholder' => 'Required Select...',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                            ->select('p, s')
                            ->orderBy('p.surname')
                            ->addOrderBy('p.firstName')
                            ->leftJoin('p.primaryRole', 'r')
                            ->leftJoin('p.staff', 's')
                            ->where('r.category <> :cat')
                            ->setParameter('cat', 'student')
                        ;
                    },

                ]
            )
            ->add('childDataAccess', ToggleType::class,
                [
                    'label' => 'Receive Reporting',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('contactCall', ToggleType::class,
                [
                    'label' => 'Contact by Phone Call',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('contactSMS', ToggleType::class,
                [
                    'label' => 'Contact by SMS',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('contactEmail', ToggleType::class,
                [
                    'label' => 'Contact by E-Mail',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('contactMail', ToggleType::class,
                [
                    'label' => 'Contact by Mail',
                    'button_merge_class' => 'btn-sm',
                ]
            )
            ->add('comment', TextareaType::class,
                [
                    'label' => 'Comment',
                    'attr' => [
                        'rows' => 1,
                    ],
                    'required' => false,
                ]
            )
            ->add('id', HiddenType::class)
            ->add('sequence', HiddenType::class)
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
                'data_class' => FamilyMemberAdult::class,
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
        return 'family_adult';
    }
}