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
use App\Entity\Person;
use App\Entity\YearGroup;
use App\Form\Subscriber\HouseSubscriber;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class YearGroupType
 * @package App\Form
 */
class YearGroupType extends AbstractType
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
                    'label'       => 'school.year_group.name.label',
                    'attr'      => [
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label'       => 'school.year_group.name_short.label',
                    'attr'      => [
                        'class' => 'form-control-sm',
                    ],
                ]
            )
            ->add('headOfYear', EntityType::class,
                [
                    'label'       => 'school.year_group.head_of_year.label',
                    'class'     => Person::class,
                    'placeholder' => 'school.year_group.head_of_year.placeholder',
                    'choice_label' => 'fullName',
                    'attr'      => [
                        'class' => 'form-control-sm',
                    ],
                    'required' => false,
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
                'data_class'         => YearGroup::class,
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
        return 'year_group';
    }
}