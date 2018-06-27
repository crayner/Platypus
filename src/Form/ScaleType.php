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
 * Date: 26/06/2018
 * Time: 20:02
 */
namespace App\Form;

use App\Entity\Scale;
use App\Entity\ScaleGrade;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScaleType
 * @package App\Form
 */
class ScaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $scale = $options['data'];
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'scale.name.label',
                    'help' => 'scale.name.help',
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'scale.name_short.label',
                    'help' => 'scale.name_short.help',
                ]
            )
            ->add('usage', TextType::class,
                [
                    'label' => 'scale.usage.label',
                    'help' => 'scale.usage.help',
                ]
            )
            ->add('lowestAcceptable', EntityType::class,
                [
                    'label' => 'scale.lowest_acceptable.label',
                    'help' => 'scale.lowest_acceptable.help',
                    'choice_label' => 'value',
                    'class' => ScaleGrade::class,
                    'query_builder' => function(EntityRepository $er) use ($scale) {
                        return $er->createQueryBuilder('g')
                            ->orderBy('g.sequence', 'ASC')
                            ->leftJoin('g.scale', 's')
                            ->where('s.id = :scale')
                            ->setParameter('scale', $scale->getId())
                        ;
                    },
                    'placeholder' => '',
                    'required' => false,
                    'choice_translation_domain' => false,
                ]
            )
            ->add('active', ToggleType::class,
                [
                    'label' => 'scale.active.label',
                ]
            )
            ->add('numeric', ToggleType::class,
                [
                    'label' => 'scale.numeric.label',
                    'help' => 'scale.numeric.help',
                ]
            )
            ->add('grades', CollectionType::class,
                [
                    'label' => 'scale.grades.label',
                    'entry_type' => ScaleGradeType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'sort_manage' => true,
                    'button_merge_class' => 'btn-sm',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'School',
                'data_class' => Scale::class,
            ]
        );
    }
}