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
     * @var array
     */
    private $data = [];

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
        $this->data = [];
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
            ->validateTemplate($this->getTemplate($templateName));

         $props = [];
         $props['locale'] = $this->getRequest()->get('_locale') ?: 'en';
         $props['template'] = $this->getTemplate($templateName);
         $props['form'] =  $this->extractForm($form->createView());
         $props['data'] =  $this->extractFormData($props['form']);
         $props['errors'] = $this->getFormErrors($form);
         $props['translations'] = [
             'object' => 'Must be an Object, not an Array',
         ];
dump($props['template']);
         return new \Twig_Markup($this->getTwig()->render('Default/renderForm.html.twig',
             [
                 'props' => $props,
             ]
         ), 'html');
    }

    /**
     * validateTemplate
     *
     * @param array $template
     * @return array
     */
    private function validateTemplate(array $template): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'form',
        ]);
        $resolver->setDefaults([
            'tabs' => false,
            'container' => false,
        ]);
        $resolver->setAllowedTypes('form', 'array');
        $resolver->setAllowedTypes('tabs', ['boolean', 'array']);
        $resolver->setAllowedTypes('container', ['boolean', 'array']);

        $this->template = $resolver->resolve($template);

        $this->template['form'] = $this->validateForm($this->template['form']);
        $this->template['container'] = $this->validateContainer($this->template['container']);
        $this->template['tabs'] = $this->validateTabs($this->template['tabs']);

        if ($this->template['container'] === false && $this->template['tabs'] === false)
            trigger_error(sprintf('The form must specify a container or a set of tabs.'),E_USER_ERROR);

        return $this->template;
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

        if (property_exists($this->getTemplateManager(), $templateName))
            return $this->getTemplateManager()->$templateName;

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

        if (isset($vars['choices']))
            $vars['choices'] = $this->translateChoices($vars);

        if ($vars['errors']->count() > 0) {
            $errors = [];
            foreach($vars['errors'] as $error)
                $errors[] = $error->getMessage();
            $vars['errors'] = $errors;
        } else
            $vars['errors'] = [];
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

        $messages = new MessageManager();

        $errorList = $this->parser->parseErrors($form);
        $errorList = is_array($errorList) ? $errorList: [];

        foreach($errorList as $q=>$w) {
            $errorList[$q]['messages'] = [];
            foreach($w['errors'] as $error) {
                $errorList[$q]['messages'][] = $error->getMessage();
                $messages->addMessage('danger', $error->getMessage(), [], false);
            }
        }

        return $messages->serialiseTranslatedMessages($this->getTranslator());
    }

    /**
     * @return FormErrorsParser
     */
    public function getParser(): FormErrorsParser
    {
        return $this->parser;
    }

    /**
     * validateRows
     *
     * @param $rows
     * @return array|boolean
     */
    private function validateRows($rows)
    {
        if ($rows === false)
            return $rows;
        if (empty($rows))
            return $rows ?: [];
        foreach($rows as $e=>$r){

            $rows[$e] = $this->validateRow($r);
        }
        return $rows;

    }

    /**
     * validateRow
     *
     * @param $row
     * @return array
     */
    private function validateRow($row)
    {
        if ($row === false)
            return $row;
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'class',
            'columns',
        ]);
        $resolver->setDefaults([
        ]);
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('columns', 'array');
        $row = $resolver->resolve($row);
        if (empty($row['columns']))
            trigger_error(sprintf('An array of columns is compulsory for each row.'), E_USER_ERROR);
        $row['columns'] = $this->validateColumns($row['columns']);
        return $row;
    }

    /**
     * validateRows
     *
     * @param $rows
     * @return array
     */
    private function validateColumns($columns): array
    {
        foreach($columns as $e=>$r){
            $columns[$e] = $this->validateColumn($r);
        }
        return $columns;

    }

    /**
     * validateColumn
     *
     * @param $column
     * @return mixed
     */
    private function validateColumn($column)
    {
        if ($column === false)
            return $column;
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'form' => false,
            'label' => false,
            'label_params' => [],
            'class' => false,
            'buttons' => false,
            'container' => false,
            'collection_actions' => false,
        ]);
        $resolver->setAllowedTypes('class', ['boolean','string']);
        $resolver->setAllowedTypes('buttons', ['boolean','array']);
        $resolver->setAllowedTypes('label', ['boolean', 'string']);
        $resolver->setAllowedTypes('label_params', ['array']);
        $resolver->setAllowedTypes('container', ['boolean', 'array']);
        $resolver->setAllowedTypes('form', ['array', 'boolean']);
        $resolver->setAllowedTypes('collection_actions', ['boolean']);
        $column = $resolver->resolve($column);
        $column['container'] = $this->validateContainer($column['container']);
        $column['buttons'] = $this->validateButtons($column['buttons']);

        return $column;
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
            'rows' => false,
            'headerRow' => false,
            'collection' => false,
        ]);
        $resolver->setAllowedTypes('class', ['string', 'boolean']);
        $resolver->setAllowedTypes('panel', ['array', 'boolean']);
        $resolver->setAllowedTypes('rows', ['array', 'boolean']);
        $resolver->setAllowedTypes('headerRow', ['array', 'boolean']);
        $resolver->setAllowedTypes('collection', ['array', 'boolean']);
        $container = $resolver->resolve($container);

        $container['panel'] = $this->validatePanel($container['panel']);

        if (($container['panel'] !== false && $container['class'] !== false) || ( $container['panel'] === false && $container['class'] === false))
            trigger_error(sprintf('Containers must specify one of a panel (%s) or a class (%s), but not both.', $container['panel']['colour'], $container['class']), E_USER_ERROR);

        $container['collection'] = $this->validateCollection($container['collection']);

        $container['headerRow'] = $this->validateRow($container['headerRow']);
        $container['rows'] = $this->validateRows($container['rows']);

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
            'rows' => [],
            'collectionRows' => false,
            'headerRow' => false,
        ]);
        $resolver->setAllowedTypes('colour', ['string']);
        $resolver->setAllowedTypes('label', ['string']);
        $resolver->setAllowedTypes('label_params', ['array']);
        $resolver->setAllowedTypes('rows', ['array']);
        $resolver->setAllowedTypes('headerRow', ['array', 'boolean']);
        $resolver->setAllowedTypes('collectionRows', ['array', 'boolean']);
        $resolver->setAllowedTypes('description_params', ['array']);
        $resolver->setAllowedTypes('description', ['boolean', 'string']);
        $resolver->setAllowedTypes('buttons', ['boolean', 'array']);
        $panel = $resolver->resolve($panel);

        $panel['buttons'] = $this->validateButtons($panel['buttons']);
        $panel['rows'] = $this->validateRows($panel['rows']);
        $panel['collectionRows'] = $this->validateRows($panel['collectionRows']);
        $panel['headerRow'] = $this->validateRows($panel['headerRow']);

        if ($panel['label'])
            $panel['label'] = $this->getTranslator()->trans($panel['label'], $panel['label_params'], $this->getTemplateManager()->getTranslationDomain());
        if ($panel['description'])
            $panel['description'] = $this->getTranslator()->trans($panel['description'], $panel['description_params'], $this->getTemplateManager()->getTranslationDomain());

        return $panel;
    }

    /**
     * validateButtons
     *
     * @param $buttons
     * @return mixed
     */
    private function validateButtons($buttons)
    {
        if ($buttons === false)
            return $buttons;

        foreach($buttons as $q=>$w)
        {
            $buttons[$q] = $this->validateButton($w);
        }
        return $buttons;
    }

    /**
     * validateButton
     *
     * @param $button
     * @return mixed
     */
    private function validateButton($button)
    {
        if ($button === false)
            return $button;
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'type',
        ]);
        $resolver->setDefaults([
            'mergeClass' => '',
            'style' => false,
            'options' => [],
            'url' => false,
            'url_options' => [],
            'url_type' => 'json',
        ]);
        $resolver->setAllowedTypes('type', ['string']);
        $resolver->setAllowedTypes('mergeClass', ['string']);
        $resolver->setAllowedTypes('style', ['boolean','array']);
        $resolver->setAllowedTypes('options', ['array']);
        $resolver->setAllowedTypes('url_options', ['array']);
        $resolver->setAllowedTypes('url', ['boolean','string']);
        $resolver->setAllowedTypes('url_type', ['string']);
        $resolver->setAllowedValues('type', ['save','submit', 'add', 'delete']);
        $resolver->setAllowedValues('url_type', ['redirect', 'json']);
        $button = $resolver->resolve($button);
        return $button;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * extractFormData
     *
     * @param $form
     * @return array
     */
    public function extractFormData($form)
    {
        $data = [];
        if (count($form['children']) > 0) {
            foreach ($form['children'] as $child) {
                $data[$child['name']] = $this->extractFormData($child);
            }
        } else {
            $data =  $form['value'];
            if (empty($form['value']) && $form['value'] !== $form['data'])
                $data =  $form['data'];
        }

        $this->data = $data;
        return $this->data;
    }

    /**
     * validateForm
     *
     * @param array $form
     * @return array
     */
    private function validateForm(array $form): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'url',
        ]);
        $resolver->setDefaults([
            'method' => 'post',
            'encType' => 'application/x-www-form-urlencoded',
        ]);
        $resolver->setAllowedTypes('url', ['string']);
        $resolver->setAllowedTypes('method', ['string']);
        $resolver->setAllowedTypes('encType', ['string']);
        $resolver->setAllowedValues('encType', ['application/x-www-form-urlencoded', 'text/plain', 'multipart/form-data']);
        $resolver->setAllowedValues('method', ['post', 'get']);

        $form = $resolver->resolve($form);
        return $form;
    }


    /**
     * validateTabs
     *
     * @param $tabs
     * @return array|boolean
     */
    private function validateTabs($tabs)
    {
        if ($tabs === false)
            return $tabs;

        foreach($tabs as $q=>$tab){
            $resolver = new OptionsResolver();
            $resolver->setRequired([
                'name',
                'container',
            ]);
            $resolver->setDefaults([
                'label' => false,
                'label_params' => [],
                'display' => true,
            ]);

            if (!empty($tab['display']) && is_string($tab['display'])) {
                $method = $tab['display'];
                $tab['display'] = $this->$method();
            }
            $resolver->setAllowedTypes('label', ['boolean','string']);
            $resolver->setAllowedTypes('display', ['boolean']);
            $resolver->setAllowedTypes('name', ['string']);
            $resolver->setAllowedTypes('container', ['array']);
            $resolver->setAllowedTypes('label_params', ['array']);

            $tab['container'] = $this->validateContainer($tab['container']);

            $tabs[$q] = $resolver->resolve($tab);
        }

        return $tabs;
    }

    /**
     * validateCollection
     *
     * @param $collection
     * @return array
     */
    private function validateCollection($collection)
    {
        if ($collection === false)
            return $collection;
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'form',
            'rows',
        ]);
        $resolver->setDefaults([
            'buttons' => [],
        ]);
        $resolver->setAllowedTypes('form', ['string']);
        $resolver->setAllowedTypes('rows', ['array']);
        $resolver->setAllowedTypes('buttons', ['array']);
        $collection = $resolver->resolve($collection);
        $collection['rows'] = $this->validateRows($collection['rows']);
        $collection['buttons'] = $this->validateButtons($collection['buttons']);
        return $collection;
    }

    /**
     * translateChoices
     *
     * @param array $vars
     * @return array
     */
    private function translateChoices(array $vars): array
    {
        $domain = $vars['choice_translation_domain'];
        if ($domain === false)
            return $vars['choices'];
        if (empty($domain))
            $domain = $vars['translation_domain'];
        if ($domain === false)
            return $vars['choices'];
        if (empty($domain))
            $domain = $this->getTemplateManager()->getTranslationDomain();
        if ($domain === false)
            return $vars['choices'];

        foreach($vars['choices'] as $choice)
        {
            if (is_object($choice->data))
                return $vars['choices'];
            $choice->label = $this->getTranslator()->trans($choice->label, [], $domain);
        }
        return $vars['choices'];
    }
}