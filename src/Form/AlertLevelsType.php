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
 * Date: 18/06/2018
 * Time: 18:31
 */
namespace App\Form;

use App\Entity\AlertLevel;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AlertLevelsType
 * @package App\Form
 */
class AlertLevelsType extends AbstractType
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
            ->add('alertLevels', CollectionType::class,
                [
                    'entry_type' => AlertLevelType::class,
                    'entry_options' => [
                        'data_class' => AlertLevel::class,
                    ],
                    'allow_add' => true,
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
        $resolver->setDefaults([
            'translation_domain' => 'System',
        ]);
    }
}