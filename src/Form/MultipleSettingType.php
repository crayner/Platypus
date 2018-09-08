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
 * Date: 16/06/2018
 * Time: 08:51
 */
namespace App\Form;

use App\Form\Transformer\SettingValueTransformer;
use App\Manager\RouterManager;
use App\Organism\SettingCache;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Hillrange\Form\Type\ChainedChoiceType;
use Hillrange\Form\Type\ColourType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\MultipleExpandedChoiceType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class MultipleSettingType
 * @package App\Form
 */
class MultipleSettingType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $key = trim($options['property_path'], '[]');
        $data = null;
        if ($key !== "") {
            $data = $options['all_data'][$key];
            $attr = $data->getFormAttr() ?: [];
            $additional = [];
            switch ($data->getType()) {
                case 'array':
                    $formType = TextareaType::class;
                    $attr = ['rows' => '5',];
                    break;
                case 'html':
                    $formType = CKEditorType::class;
                    $additional = ['config_name' => 'setting_toolbar'];
                    $attr = ['rows' => '3'];
                    break;
                case 'boolean':
                    $formType = ToggleType::class;
                    break;
                case 'colour':
                    $formType = ColourType::class;
                    break;
                case 'image':
                    $formType = ImageType::class;
                    $route = $this->routerManager->getCurrentRoute();
                    $additional = [
                        'fileName' => $data->getName(),
                        'deletePhoto' => $this->router->generate('delete_setting_image', ['name' => $data->getName(), 'route' => $route]),
                    ];
                    break;
                case 'choice':
                    $formType = ChoiceType::class;
                    $choices = $data->__get('choice');
                    if (empty($data->__get('translateChoice'))) {
                        if (is_null($data->__get('translateChoice'))) {
                            foreach ($choices as $value)
                                $x[$value] = $value;
                        } else {
                            $x = $choices;
                        }
                    } else {
                        foreach ($choices as $value)
                            $x[$data->getName() . '.' . $value] = $value;
                    }
                    $additional = [
                        'choices' => $x,
                        'placeholder' => false,
                    ];
                    break;
                case 'multiChoice':
                    $formType = MultipleExpandedChoiceType::class;
                    $choices = $data->__get('choice');
                    $additional = [
                        'choices' => $choices,
                        'placeholder' => false,
                        'expanded_attr' => [
                            'class' => 'form-control-sm',
                        ],
                    ];
                    break;
                case 'integer':
                    $formType = TextType::class;
                    foreach($data->getValidators() as $validator)
                    {
                        if (get_class($validator) === Range::class)
                        {
                            $formType = ChoiceType::class;
                            $choices = [];
                            for($i=$validator->min; $i<=$validator->max; $i++)
                                $choices[$i] = $i;
                            $additional = [
                                'choices' => $choices,
                                'placeholder' => false,
                                'choice_translation_domain' => false,
                            ];
                            break;
                        }
                    }
                    break;
                case 'chainedChoice':
                    $formType = ChainedChoiceType::class;
                    $additional = $data->getChainOptions() ?: [];
                    dd($additional);
                    break;
                case 'entity':
                    $formType = EntityType::class;
                    $additional = $data->getEntityOptions() ?: [];
                    break;
                case 'system':
                    $formType = HiddenType::class;
                    $additional = $data->getEntityOptions() ?: [];
                    break;
                case 'text':
                    $formType = TextareaType::class;
                    $attr = ['rows' => '5',];
                    break;
                default:
                    $formType = TextType::class;
            }
            $builder
                ->add('value', $formType,
                    array_merge($additional, [
                        'label' => $data->getDisplayName(),
                        'help' => $data->getDescription(),
                        'translation_domain' => empty($data->getTranslateChoice()) ? 'Setting' : $data->getTranslateChoice(),
                        'attr' => $attr,
                        'required' => false,
                        'constraints' => $data->getValidators(),
                    ])
                )
            ;
        } else
            $builder
                ->add('value', TextType::class);

        $builder->get('value')->addViewTransformer(new SettingValueTransformer(empty($data) ? 'text' : $data->getType()));
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'multiple_settings';
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'all_data',
                'section_name',
                'section_description',
            ]
        );
        $resolver->setDefaults(
            [
                'translation_domain' => 'Setting',
                'data_class' => SettingCache::class,
            ]
        );
    }

    /**
     * buildView
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['section_name'] = $options['section_name'];
        $view->vars['section_description'] = $options['section_description'];
    }

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RouterManager
     */
    private $routerManager;

    /**
     * MultipleSettingType constructor.
     * @param RouterInterface $router
     * @param RequestStack $stack
     */
    public function __construct(RouterInterface $router, RouterManager $routerManager)
    {
        $this->router = $router;
        $this->routerManager = $routerManager;
    }
}