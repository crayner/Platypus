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
 * Time: 16:41
 */
namespace App\Form;

use App\Manager\CollectionManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CollectionType
 * @package App\Form
 */
class CollectionType extends AbstractType
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
            ->add('collection', \Hillrange\Form\Type\CollectionType::class,
                [
                    'entry_type'        => $options['entry_type'],
                    'entry_options'     => [
                        'data_class'        => $options['entry_options_data_class'],
                    ],
                    'allow_add'         => $options['allow_add'],
                    'allow_delete'      => $options['allow_delete'],
                    'sort_manage'       => $options['sort_manage'],
                    'redirect_route'    => $options['redirect_route'],
                    'unique_key'            => $options['unique_key'],
                    'allow_up'              => $options['allow_up'],
                    'allow_down'            => $options['allow_down'],
                    'allow_duplicate'       => $options['allow_duplicate'],
                    'route'                 => $options['route'],
                    'route_params'          => $options['route_params'],
                    'display_script'        => $options['display_script'],
                    'add_button'            => $options['add_button'],
                    'remove_button'         => $options['remove_button'],
                    'up_button'             => $options['up_button'],
                    'down_button'           => $options['down_button'],
                    'duplicate_button'      => $options['duplicate_button'],
                    'button_merge_class'    => $options['button_merge_class'],
                    'removal_warning'       => $options['removal_warning'],
                ]
            )
        ;
        $this->setBlockPrefix($options['entry_type']);
    }

    /**
     * @var string
     */
    private $blockPrefix;

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'        => CollectionManager::class,
                'allow_add'         => true,
                'allow_delete'      => true,
                'sort_manage'       => false,
                'redirect_route'    => null,
                'unique_key'            => 'id',
                'allow_up'              => false,
                'allow_down'            => false,
                'allow_duplicate'       => false,
                'route'                 => '',
                'route_params'          => [],
                'display_script'        => false,
                'add_button'            => '',
                'remove_button'         => '',
                'up_button'             => '',
                'down_button'           => '',
                'duplicate_button'      => '',
                'button_merge_class'    => '',
                'removal_warning'       => null,
            ]
        );
        $resolver->setRequired(
            [
                'entry_type',
                'entry_options_data_class',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'collection_' . StringUtil::fqcnToBlockPrefix($this->blockPrefix);
    }

    /**
     * @param string $blockPrefix
     * @return CollectionType
     */
    public function setBlockPrefix(string $blockPrefix): CollectionType
    {
        $this->blockPrefix = $blockPrefix;
        return $this;
    }
}