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
 * Date: 15/10/2018
 * Time: 12:33
 */
namespace App\Manager;

use App\Util\FormErrorsParser;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FormManager
 * @package App\Manager
 */
class FormManager
{
    /**
     * @var FormManagerInterface
     */
    private $templateManager;

    /**
     * @return FormManagerInterface
     */
    public function getTemplateManager(): FormManagerInterface
    {
        return $this->templateManager;
    }

    /**
     * @param FormManagerInterface $templateManager
     * @return FormManager
     */
    public function setTemplateManager(FormManagerInterface $templateManager): FormManager
    {
        $this->templateManager = $templateManager;
        return $this;
    }
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormErrorsParser
     */
    private $parser;

    /**
     * CollectionManager constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig, RequestStack $stack, TranslatorInterface $translator, FormErrorsParser $parser)
    {
        $this->twig = $twig;
        $this->stack = $stack;
        $this->translator = $translator;
        $this->parser = $parser;
    }

    /**
     * getRequest
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->getStack()->getCurrentRequest();
    }

    /**
     * renderForm
     *
     * @param FormInterface $form
     * @param FormManagerInterface $templateManager
     * @param string $templateName
     * @return \Twig_Markup
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderForm(FormInterface $form, FormManagerInterface $templateManager, string $templateName = 'template')
    {
         $this->setTemplateManager($templateManager)
            ->isTemplateValid($templateName);

         $props = [];
         $props['locale'] = $this->getRequest()->get('_locale') ?: 'en';
         $props['template'] = $this->getTemplate($templateName);
         $props['form'] =  $this->extractForm($form->createView());
         $props['errors'] =  $this->getFormErrors($form);
         $props['translations'] = [
             'object' => 'Must be an Object, not an Array',
         ];


         return new \Twig_Markup($this->getTwig()->render('Default/renderForm.html.twig',
             [
                 'props' => $props,
             ]
         ), 'html');
    }

    /**
     * isTemplateValid
     *
     * @param string $templateName
     * @return bool
     */
    private function isTemplateValid(string $templateName): bool
    {
        $template = $this->getTemplate($templateName);


        $resolver = new OptionsResolver();
        $resolver->setRequired([]);
        $resolver->setDefaults([
            'useTabs' => false,
            'tabs' => false,
            'method' => 'post',
        ]);
        $resolver->setAllowedTypes('useTabs', 'boolean');
        $resolver->setAllowedTypes('tabs', ['boolean', 'array']);
        $resolver->setAllowedValues('method', ['post', 'get']);

        $template = $resolver->resolve($template);

        if ($template['useTabs'] && empty($template['tabs']))
            trigger_error(sprintf('No tabs have been defined!'), E_USER_ERROR);

        if ($template['useTabs'])
        {
            foreach($template['tabs'] as $q=>$w)
            {
                $resolver = new OptionsResolver();
                $resolver->setRequired([
                    'label',
                    'name',
                ]);
                $resolver->setDefaults([
                    'display' => true,
                    'message' => $w['name'].'Messages',
                    'translation' => false,
                    'rows' => false,
                    'container' => false,
                ]);
                $resolver->setAllowedTypes('name', 'string');
                $resolver->setAllowedTypes('display', 'boolean');
                $resolver->setAllowedTypes('label', 'string');
                $resolver->setAllowedTypes('rows', ['boolean', 'array']);
                $resolver->setAllowedTypes('container', ['boolean', 'array']);

                if (isset($w['display']) && is_string($w['display']) && method_exists($this->getTemplateManager(), $w['display'])) {
                    $method = $w['display'];
                    $w['display'] = $this->getTemplateManager()->$method();
                }

                $template['tabs'][$q] = $resolver->resolve($w);

                $w = $template['tabs'][$q];
                $template['tabs'][$q]['rows'] = $this->isValidRows($template['tabs'][$q]['rows']);
                $template['tabs'][$q]['container'] = $this->validateContainer($template['tabs'][$q]['container']);
            }
        } else {
            dd('Crap!');
        }

        $this->template = $template;

        return true;
    }

    /**
     * @var array
     */
    private $template;

    /**
     * getTemplate
     *
     * @param string $templateName
     * @return array
     */
    private function getTemplate(string $templateName): array
    {
        if (is_array($this->template))
            return $this->template;
        $templateName = 'get' . ucfirst($templateName);
        if (! method_exists($this->getTemplateManager(), $templateName))
            trigger_error(sprintf('No template "%s" was found in %s Manager.', $templateName, get_class($this->getTemplateManager())), E_USER_ERROR);
        return $this->getTemplateManager()->$templateName();
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig(): \Twig_Environment
    {
        return $this->twig;
    }

    /**
     * @return RequestStack
     */
    public function getStack(): RequestStack
    {
        return $this->stack;
    }

    /**
     * extractForm
     *
     * @param FormView $formView
     * @return array
     */
    public function extractForm(FormView $formView): array
    {
        $vars = $formView->vars;
        $vars['children'] = [];
        foreach($formView->children as $child)
        {
            $vars['children'][] = $this->extractForm($child);
        }

        if (is_object($vars['value'])){
            $vars['data_id'] = null;
            $vars['data_toString'] = null;
            if (method_exists($vars['value'], 'getId'))
                $vars['data_id'] = $vars['value']->getId();
            if (method_exists($vars['value'], 'getName'))
                $vars['data_toString'] = $vars['value']->getName();
            if (method_exists($vars['value'], '__toString'))
                $vars['data_toString'] = $vars['value']->__toString();
        }
        if (isset($vars['prototype']) && $vars['prototype'] instanceof FormView)
        {
            $vars['prototype'] = $this->extractForm($vars['prototype']);
        }

        if ($vars['required'])
            $vars['required'] = $this->getTranslator()->trans('form.required', [], 'FormTheme');
        else
            $vars['required'] = '';

        if (! empty($vars['label']))
        $vars['label'] = $this->getTranslator()->trans($vars['label'], [], $vars['translation_domain']);
        else
            $vars['label'] = '';

        if (! empty($vars['help']))
            $vars['help'] = $this->getTranslator()->trans($vars['help'], $vars['help_params'], $vars['translation_domain']);
        else
            $vars['help'] = '';

        unset($vars['form']);
        return $vars;
    }

    /**
     * getFormErrors
     *
     * Main Twig extension. Call this in Twig to get formatted output of your form errors.
     * Note that you have to provide form as Form object, not FormView.
     * @param FormInterface $form
     * @param string $transDomain
     * @return array
     */
    public function getFormErrors(FormInterface $form, $transDomain = 'System'): array
    {
        if (!$form->isSubmitted()) return [];

        $errorList = $this->parser->parseErrors($form);

dd($errorList);

        return is_array($errorList) ? $errorList: [];
    }

    /**
     * @return FormErrorsParser
     */
    public function getParser(): FormErrorsParser
    {
        return $this->parser;
    }

    /**
     * isValidRows
     *
     * @param $rows
     * @return array
     */
    private function isValidRows($rows): array
    {
        foreach($rows as $e=>$r){
            $resolver = new OptionsResolver();
            $resolver->setRequired([
                'class',
                'columns',
            ]);
            $resolver->setDefaults([
            ]);
            $resolver->setAllowedTypes('class', 'string');
            $resolver->setAllowedTypes('columns', 'array');
            $rows[$e] = $resolver->resolve($r);
            if (empty($r['columns']))
                trigger_error(sprintf('An array of columns is compulsory for each row.'), E_USER_ERROR);
            $r['columns'] = $this->isValidColumns($r['columns']);
        }
        return $rows;

    }

    /**
     * isValidRows
     *
     * @param $rows
     * @return array
     */
    private function isValidColumns($columns): array
    {
        foreach($columns as $e=>$r){
            $resolver = new OptionsResolver();
            $resolver->setRequired([
                'class'
            ]);
            $resolver->setDefaults([
                'form' => false,
            ]);
            $resolver->setAllowedTypes('class', 'string');
            $resolver->setAllowedTypes('form', ['array', 'boolean']);
            $columns[$e] = $resolver->resolve($r);
        }
        return $columns;

    }

    /**
     * getTranslator
     *
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * validateContainer
     *
     * @param $container
     * @return array
     */
    private function validateContainer($container)
    {
        if ($container === false)
            return $container;
        $resolver = new OptionsResolver();
        $resolver->setRequired([
        ]);
        $resolver->setDefaults([
            'panel' => false,
            'class' => false,
        ]);
        $resolver->setAllowedTypes('class', ['string', 'boolean']);
        $resolver->setAllowedTypes('panel', ['array', 'boolean']);
        $container = $resolver->resolve($container);

        $container['panel'] = $this->validatePanel($container['panel']);
        
        if ($container['panel'] && $container['class'])
            trigger_error(sprintf('Containers must specify a panel (%s) or a class (%s), not both.', $container['panel']['colour'], $container['class']), E_USER_ERROR);

        return $container;
    }

    /**
     * validatePanel
     *
     * @param $panel
     * @return array
     */
    private function validatePanel($panel)
    {
        if ($panel === false)
            return $panel;
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'label',
        ]);
        $resolver->setDefaults([
            'colour' => 'info',
            'description' => false,
            'buttons' => false,
            'label_params' => [],
            'description_params' => [],
        ]);
        $resolver->setAllowedTypes('colour', ['string']);
        $resolver->setAllowedTypes('label', ['string']);
        $resolver->setAllowedTypes('label_params', ['array']);
        $resolver->setAllowedTypes('description_params', ['array']);
        $resolver->setAllowedTypes('description', ['boolean', 'string']);
        $resolver->setAllowedTypes('buttons', ['boolean', 'array']);
        $panel = $resolver->resolve($panel);

        $panel['buttons'] = $this->validateButtons($panel['buttons']);

        if ($panel['label'])
            $panel['label'] = $this->getTranslator()->trans($panel['label'], $panel['label_params'], $this->getTemplateManager()->getTranslationDomain());
        if ($panel['description'])
            $panel['description'] = $this->getTranslator()->trans($panel['description'], $panel['description_params'], $this->getTemplateManager()->getTranslationDomain());

        return $panel;
    }
    
    private function validateButtons($buttons)
    {
        if ($buttons === false)
            return $buttons;

        foreach($buttons as $q=>$w)
        {
            $resolver = new OptionsResolver();
            $resolver->setRequired([
                'type',
            ]);
            $resolver->setDefaults([
                'mergeClass' => '',
            ]);
            $resolver->setAllowedTypes('type', ['string']);
            $resolver->setAllowedTypes('mergeClass', ['string']);
            $resolver->setAllowedValues('type', ['save']);
            $w = $resolver->resolve($w);


        }
        return $buttons;
    }
}