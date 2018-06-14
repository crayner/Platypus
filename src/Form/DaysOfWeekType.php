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
 * Time: 12:12
 */
namespace App\Form;

use App\Organism\DaysOfWeek;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DaysOfWeekType
 * @package App\Form
 */
class DaysOfWeekType extends AbstractType
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
            ->add('days', CollectionType::class,
                [
                    'entry_type' => DayOfWeekType::class,
                    'allow_up' => true,
                    'allow_down' => true,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'sort_manage' => true,
                    'button_merge_class' => 'btn-sm',

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
                'data_class' => DaysOfWeek::class,
                'translation_domain' => 'School',
                'constraints' => [
                    new \App\Validator\DaysOfWeek(),
                ],
            ]
        );
    }
}