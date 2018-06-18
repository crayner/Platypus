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
use Hillrange\Form\Util\ButtonManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CollectionManagerType
 * @package App\Form
 */
class CollectionManagerType extends AbstractType
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
                    'entry_options'     => array_merge([
                        'data_class'        => $options['entry_options_data_class'],
                    ], $options['entry_options']),
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
                'entry_options'         => [],
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
     * @return CollectionManagerType
     */
    public function setBlockPrefix(string $blockPrefix): CollectionManagerType
    {
        $this->blockPrefix = $blockPrefix;
        return $this;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['button_merge_class'])
        {
            if (empty($options['add_button']))
                $options['add_button'] = $this->buttonManager->addButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['remove_button']))
                $options['remove_button'] = $this->buttonManager->removeButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['up_button']))
                $options['up_button'] = $this->buttonManager->upButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['down_button']))
                $options['down_button'] = $this->buttonManager->downButton(['mergeClass' => $options['button_merge_class']]);
            if (empty($options['duplicate_button']))
                $options['duplicate_button'] = $this->buttonManager->duplicateButton(['mergeClass' => $options['button_merge_class']]);
        }

        $view->vars['allow_up']             = $options['sort_manage'];
        $view->vars['allow_down']           = $options['sort_manage'];
        $view->vars['allow_duplicate']      = $options['allow_duplicate'];
        $view->vars['unique_key']           = $options['unique_key'];
        $view->vars['route']                = $options['route'];
        $view->vars['redirect_route']       = $options['redirect_route'];
        $view->vars['route_params']         = $options['route_params'];
        $view->vars['display_script']       = $options['display_script'];
        $view->vars['add_button']           = $options['add_button'];
        $view->vars['remove_button']        = $options['remove_button'];
        $view->vars['up_button']            = $options['up_button'];
        $view->vars['down_button']          = $options['down_button'];
        $view->vars['removal_warning']      = $options['removal_warning'];
        $view->vars['allow_add']            = $options['allow_add'];
        $view->vars['allow_delete']         = $options['allow_delete'];
    }

    /**
     * @var ButtonManager
     */
    private $buttonManager;

    /**
     * CollectionManagerType constructor.
     * @param CollectionSubscriber $collectionSubscriber
     */
    public function __construct(ButtonManager $buttonManager)
    {
        $this->buttonManager = $buttonManager;
    }
}