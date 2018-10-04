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

use App\Form\Subscriber\MultiEnumSubscriber;
use App\Form\Transformer\SettingValueTransformer;
use App\Manager\RouterManager;
use App\Organism\SettingCache;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Hillrange\Form\Type\ChainedChoiceType;
use Hillrange\Form\Type\ColourType;
use Hillrange\Form\Type\DateType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\ImageType;
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
            $attr = $data->getFormAttr();
            $additional = [];
            switch ($data->getSetting()->getSettingType()) {
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
                case 'date':
                    $formType = DateType::class;
                    $years = [];
                    for($i=intval(date('Y')); $i>=intval(date('Y', strtotime('-25 Years'))); $i--)
                        $years[] = strval($i);
                    $additional = [
                        'years' => $years,
                    ];
                    break;
                case 'image':
                    $formType = ImageType::class;
                    $route = $this->routerManager->getCurrentRoute();
                    $additional = [
                        'fileName' => $data->getSetting()->getName(),
                        'deletePhoto' => $this->router->generate('delete_setting_image', ['name' => $data->getSetting()->getName(), 'route' => $route]),
                    ];
                    break;
                case 'enum':
                    $formType = ChoiceType::class;
                    $additional = [
                        'choices' => $data->getSetting()->getChoices(),
                        'placeholder' => $data->getPlaceholder(),
                    ];
                    break;
                case 'multiEnum':
                    $formType = ChoiceType::class;
                    $additional = [
                        'choices' => $data->getSetting()->getChoices(),
                        'multiple' => true,
                    ];
                    break;
                case 'integer':
                    $formType = TextType::class;
                    foreach($data->getSetting()->getValidators() as $validator)
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
                    break;
                case 'entity':
                    $formType = EntityType::class;
                    break;
                case 'system':
                    $formType = HiddenType::class;
                    break;
                case 'text':
                    $formType = TextareaType::class;
                    $attr = ['rows' => '3',];
                    break;
                case 'blob':
                    $formType = TextareaType::class;
                    $attr = ['rows' => '5',];
                    break;
                default:
                    $formType = TextType::class;
            }
            $builder
                ->add('value', $formType,
                    array_merge($additional, [
                        'label' => $data->getSetting()->getDisplayName(),
                        'help' => $data->getSetting()->getDescription(),
                        'attr' => $attr,
                        'required' => false,
                        'constraints' => $data->getSetting()->getValidators(),
                    ])
                )
            ;
        } else
            $builder
                ->add('value', TextType::class);

        $builder->get('value')->addViewTransformer(new SettingValueTransformer(empty($data) ? 'text' : $data->getSetting()->getSettingType()));
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