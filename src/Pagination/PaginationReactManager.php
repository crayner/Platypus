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
 * Date: 18/08/2018
 * Time: 11:22
 */
namespace App\Pagination;

use App\Manager\MessageManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PaginationReactManager
 * @package App\Pagination
 */
abstract class PaginationReactManager implements PaginationInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * PaginationReactManager constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, MessageManager $messageManager)
    {
        $this->request = $request->getCurrentRequest();
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;

        if (method_exists($this, 'setSpecificTranslations'))
            $this->setSpecificTranslations();
    }

    /**
     * getDisplayProperties
     *
     * @return array
     * @throws \Exception
     */
    public function getDisplayProperties(): array
    {
        $props = [];

        $props['locale'] = $this->getRequest()->get('_locale') ?: 'en';
        $props['name'] = $this->getName();
        $props['displaySearch'] = $this->isDisplaySearch();
        $props['displaySort'] = $this->isDisplaySort();
        $props['displayFilter'] = $this->isDisplayFilter();
        $props['filter'] = $this->getFilter();
        $props['sortByList'] = $this->getSortByList();
        $props['sortOptions'] = $this->getSortList();
        $props['results'] = $this->getAllResults();
        $props['offset'] = $this->getOffset();
        $props['search'] = $this->getSearch() ?: '';
        $props['sort'] = $this->getSort() ?: '';
        $props['limit'] = $this->getLimit();
        $props['orderBy'] = $this->getOrderBy();
        $props['caseSensitive'] = $this->getCaseSensitive();
        $props['columnDefinitions'] = $this->getColumnDefinitions();
        $props['headerDefinition'] = $this->getHeaderDefinition();
        $props['searchDefinition'] = $this->getSearchDefinition();
        $props['actions'] = $this->getActions();
        $props['filterValue'] = $this->getFilterValue();

        $props['translations'] = [];
        $props['translations'][] = 'pagination.search.label';
        $props['translations'][] = 'pagination.search.placeholder';
        $props['translations'][] = 'pagination.filter.label';
        $props['translations'][] = 'pagination.filter.placeholder';
        $props['translations'][] = 'pagination.filter.clear';
        $props['translations'][] = 'pagination.filter.by';
        $props['translations'][] = 'search';
        $props['translations'][] = 'pagination.sort.label';
        $props['translations'][] = 'pagination.limit.label';
        $props['translations'][] = 'pagination.actions';
        $props['translations'][] = 'save';
        $props['translations'][] = 'previous';
        $props['translations'][] = 'next';
        $props['translations'][] = 'case_sensitive';
        $props['translations'][] = 'order_by';
        $props['translations'][] = 'message.close';
        $props['translations'][] = 'pagination.figures.empty';
        $props['translations'][] = 'pagination.figures.one_page.one_record';
        $props['translations'][] = 'pagination.figures.one_page.two_plus';
        $props['translations'][] = 'pagination.figures.two_plus';

        $props['specificTranslations'] = $this->getSpecificTranslations();
        foreach($this->getSortList() as $name)
            $props['translations'][] = $name;

        return $props;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strtolower(str_replace('_pagination', '', $this->name ?: 'default')) .'_pagination';
    }

    /**
     * isDisplaySearch
     *
     * @return bool
     */
    public function isDisplaySearch(): bool
    {
        if (! property_exists($this, 'searchDefinition') || ! is_array($this->searchDefinition) || empty($this->searchDefinition))
            return false;

        if (! property_exists($this, 'displaySearch'))
            return true;

        return $this->displaySearch ? true : false ;
    }

    /**
     * isDisplaySort
     *
     * @return bool
     */
    public function isDisplaySort(): bool
    {
        if (is_null($this->getSortList()))
            return false;
        if (! property_exists($this, 'displaySort'))
            return true;
        return $this->displaySort ? true : false ;
    }

    /**
     * getSortList
     *
     * @return array
     */
    public function getSortList(): ?array
    {
        if (! property_exists($this, 'sortByList'))
            return null;

        $sortByList = [];
        if (!empty($this->sortByList) && is_array($this->sortByList))
            foreach ($this->sortByList as $name => $w)
                $sortByList[$name] = $name;

        return $sortByList;
    }

    /**
     * build Query
     *
     * @version    28th October 2016
     * @since      28th October 2016
     *
     * @param    boolean $count
     *
     * @return    QueryBuilder
     */
    public function buildQuery($count = false): QueryBuilder
    {
        $this->query = $this->getRepository()->createQueryBuilder($this->getAlias());
            $this
                ->setQuerySelect()
                ->setQueryJoin();

        return $this->getQuery();
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * getRepository
     *
     * @param string|null $name
     * @return ObjectRepository
     */
    public function getRepository(?string $name = null): ObjectRepository
    {
        return $this->getEntityManager()->getRepository($name ?: $this->getEntityName());
    }

    /**
     * getEntityName
     *
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * getAlias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }


    /**
     * set Query Select
     *
     * @version     13th February 2018
     * @since       27th October 2016
     * @return      PaginationManager
     */
    protected function setQuerySelect(): PaginationInterface
    {
        $selectBegin = true;
        if ($this->isFullEntity()) {
            $this->query->select($this->getAlias() . ' AS entity');
            $selectBegin = false;
        }

        if (empty($this->getSelect())  || ! is_array($this->select)) return $this;

        foreach ($this->select as $key=>$name)
        {
            if ($name === false)
                continue;
            if (is_array($name)) {
                foreach($name as $w) {
                    if ($selectBegin) {
                        $this->query->select($w);
                        $selectBegin = false;
                    } else
                        $this->query->addSelect($w);
                }
            } else if ($key === intval($key)) {
                if ($selectBegin){
                    $this->query->select($name);
                    $selectBegin = false;
                } else
                    $this->query->addSelect($name);
            } else {
                $select = $name . ' AS ' . $key;
                if ($selectBegin){
                    $this->query->select($select);
                    $selectBegin = false;
                } else
                    $this->query->addSelect($select);

            }
        }

        return $this;
    }

    /**
     * getSearchDefinition
     *
     * @return array
     */
    private function getSearchDefinition(): array
    {
        return (property_exists($this, 'searchDefinition') && is_array($this->searchDefinition) && ! empty($this->searchDefinition)) ? $this->searchDefinition : [] ;

    }

    /**
     * isFullEntity
     *
     * @return bool
     */
    protected function isFullEntity(): bool
    {
        if (! property_exists($this, 'fullEntity'))
            return false;
        return $this->fullEntity ? true : false ;
    }

    /**
     * @var array
     */
    private $sessionData;

    /**
     * @return array
     */
    public function getSessionData(): array
    {
        if (empty($this->sessionData))
        {
            $pagination = $this->getSession()->get('pagination');
            $this->sessionData = ! empty($pagination[$this->getName()]) ? $pagination[$this->getName()] : [];
        }
        return $this->sessionData;
    }

    /**
     * setSessionData
     *
     * @return PaginationInterface
     * @throws \Exception
     */
    public function setSessionData(): PaginationInterface
    {
        $sessionData = [];
        $sessionData['offset'] = $this->getOffset();
        $sessionData['search'] = $this->getSearch();
        $sessionData['sort'] = $this->getSort();
        $sessionData['limit'] = $this->getLimit();
        $sessionData['filterValue'] = $this->getFilterValue();
        $this->sessionData = $sessionData;

        $pagination = $this->getSession()->get('pagination');
        $pagination[$this->getName()] = $sessionData;
        $this->getSession()->set('pagination', $pagination);

        return $this;
    }

    /**
     * @var
     */
    private $search;

    /**
     * getSearch
     *
     * @return string
     */
    private function getSearch(): string
    {
        return $this->search = $this->search ?: isset($this->getSessionData()['search']) ? $this->getSessionData()['search'] : '';
    }

    /**
     * @var array
     */
    private $filterValue = [];

    /**
     * getSearch
     *
     * @return array
     */
    private function getFilterValue(): array
    {
        return $this->filterValue = $this->filterValue ?: isset($this->getSessionData()['filterValue']) ? $this->getSessionData()['filterValue'] : [];
    }

    /**
     * @param mixed $search
     * @return PaginationInterface
     */
    public function setSearch($search): PaginationInterface
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }

    /**
     * set Join
     *
     * @version     13th February 2018
     * @since       27th October 2016
     * @return      PaginationInterface
     */
    protected function setQueryJoin(): PaginationInterface
    {
        if (! property_exists($this, 'join'))
            return $this;
        if (empty($this->join) || !is_array($this->join))
            return $this;
        foreach ($this->join as $name => $pars)
        {
            $type = empty($pars['type']) ? 'join' : $pars['type'];
            $this->query->$type($name, $pars['alias']);
        }
        return $this;
    }

    /**
     * @var integer
     */
    private $offset;

    /**
     * getOffset
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset = $this->offset ?: isset($this->getSessionData()['offset']) ? $this->getSessionData()['offset'] : 0;
    }

    /**
     * getSession
     *
     * @return SessionInterface
     */
    private function getSession(): SessionInterface
    {
        return $this->getRequest()->getSession();
    }

    /**
     * getLimit
     *
     * @return int
     * @throws \Exception
     */
    private function getLimit(): int
    {
        if (!property_exists($this, 'limit'))
            throw new \Exception('The limit for pagination MUST be set.');

        return  isset($this->getSessionData()['limit']) ? $this->getSessionData()['limit'] : $this->limit;
    }

    /**
     * @return string
     */
    public function getTransDomain(): string
    {
        if (! property_exists($this, 'transDomain'))
            return 'pagination';
        return $this->transDomain;
    }

    /**
     * getSpecificTranslations
     *
     * @return array
     * @throws \Exception
     */
    private function getSpecificTranslations()
    {
        if (property_exists($this, 'specificTranslations'))
            $specificTranslations = $this->specificTranslations;
        else
            $specificTranslations = [];

        foreach($this->getSortList() as $name)
            $specificTranslations[] = $name;

        foreach($this->getColumnDefinitions() as $definition)
            if ($definition['label'] !== false)
                if (is_array($definition['label']))
                    foreach($definition['label'] as $heading)
                        $specificTranslations[] = $heading['label'];
                else
                    $specificTranslations[] = $definition['label'];

        $specificTranslations[] = $this->getHeaderDefinition()['title'];

        if ($this->getHeaderDefinition()['paragraph'] !== false)
            $specificTranslations[] = $this->getHeaderDefinition()['paragraph'];

        foreach($this->getFilter() as $group) {
            $specificTranslations[] = $group['label'];
            foreach ($group['fields'] as $filter)
                $specificTranslations[] = $filter['label'];
        }

        return $specificTranslations;
    }

    /**
     * getSortByList
     *
     * @return array
     */
    private function getSortByList(): array
    {
        if (!property_exists($this, 'sortByList'))
            return [];
        return $this->sortByList;
    }

    /**
     * getColumnDefinitions
     *
     * @return array
     * @throws \Exception
     */
    private function getColumnDefinitions(): array
    {
        $columnDefinitions = [];
        foreach($this->columnDefinitions as $key => $definition)
        {
            $resolver = new OptionsResolver();
            $resolver->setRequired(
                [
                    'label',
                    'name',
                ]
            );
            $resolver->setDefaults(
                [
                    'help' => false,
                    'display' => true,
                    'size' => 2,
                    'translate' => false,
                    'style' => 'text',
                    'options' => [],
                    'class' => null,
                    'select' => null,

                ]
            );
            $definition = $resolver->resolve($definition);
            $columnDefinitions[$key] = $definition;
        }

        return $columnDefinitions;
    }

    /**
     * getHeaderDefinition
     *
     * @return array
     * @throws \Exception
     */
    private function getHeaderDefinition(): array
    {
        if (! property_exists($this, 'headerDefinition') || ! is_array($this->headerDefinition))
            return [
                'title' => $this->getHeaderTitle().'.title',
                'paragraph' => false,
                'buttons' => [],
            ];

        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'title' => $this->getHeaderTitle().'.title',
                'paragraph' => false,
                'buttons' => [],
            ]
        );
        $headerDefinition = $resolver->resolve($this->headerDefinition);

        return $headerDefinition;
    }

    /**
     * @var string
     */
    private $sort;

    /**
     * getSearch
     *
     * @return string
     */
    private function getSort(): string
    {
        if (empty($this->getSortByList()))
            return '';
        reset($this->sortByList);
        $key = key($this->sortByList);
        $this->sort = $this->sort ?: isset($this->getSessionData()['sort']) ? $this->getSessionData()['sort'] : $key;

        if (empty($this->sortByList[$this->sort]))
            $this->sort = $key;

        return $this->sort;
    }

    /**
     * @var string
     */
    private $orderBy;

    /**
     * getSearch
     *
     * @return string
     */
    private function getOrderBy(): string
    {
        return $this->orderBy = $this->orderBy ?: isset($this->getSessionData()['orderBy']) ? $this->getSessionData()['orderBy'] : 'ASC';
    }

    /**
     * @var bool
     */
    private $caseSensitive;

    /**
     * getSearch
     *
     * @return string
     */
    private function getCaseSensitive(): string
    {
        return $this->caseSensitive = $this->caseSensitive ? true : isset($this->getSessionData()['caseSensitive']) ? $this->getSessionData()['caseSensitive'] : false;
    }

    /**
     * getActions
     *
     * @return array
     */
    private function getActions(): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'buttons',
            ]
        );
        $resolver->setAllowedTypes('buttons','array');
        $resolver->setDefaults(
            [
                'label' => 'pagination.actions',
                'size' => 2,
                'class' => '',
            ]
        );
        $this->actions = $resolver->resolve($this->actions);

        foreach($this->actions['buttons'] as $q=>$button)
        {
            $resolver = new OptionsResolver();
            $resolver->setRequired(
                [
                    'url',
                ]
            );
            $resolver->setDefaults(
                [
                    'type' => 'miscellaneous',
                    'label' => false,
                    'url_options' => [],
                    'classMerge' => '',
                    'style' => ['float' => 'right'],
                    'response_type' => 'json',
                    'icon' => false,
                    'colour' => '',
                ]
            );
            $button = $resolver->resolve($button);
            if (! in_array($button['response_type'], ['redirect','json']))
                throw new InvalidOptionsException(sprintf('The response_type of \'%s\' is not valid. Accepted values are [redirect, json]', $button['response_type']));
            $this->actions['buttons'][$q] = $button;
        }

        return $this->actions;
    }

    /**
     * getHeaderTitle
     *
     * @return string
     */
    public function getHeaderTitle(): string
    {
        if (property_exists($this, 'headerTitle'))
            return $this->headerTitle;

        return $this->getName() ;
    }

    /**
     * getSelect
     *
     * @return array
     */
    public function getSelect(): array
    {
        if (! empty($this->select))
            return $this->select;

        if (! property_exists($this, 'columnDefinitions'))
            throw new \Exception('The Column definitions are missing.');
        if (! is_array($this->columnDefinitions))
            throw new \Exception('The Column definitions is not an array.');

        $this->select = [];

        foreach($this->columnDefinitions as $name=>$definition)
        {
            if (isset($definition['select'])) {
                if ($definition['select'] !== false)
                    $this->select[$name] = $definition['select'];
            } else {
                $this->columnDefinitions[$name]['select'] = $name;
                $this->select[] = $name;
            }
        }

        return $this->select;
    }

    /**
     * @var bool
     */
    private $displayFilter = false;

    /**
     * isDisplayFilter
     *
     * @return bool
     */
    private function isDisplayFilter(): bool
    {
        if (empty($this->getFilter()))
            return $this->displayFilter =false;

        return $this->displayFilter = true;
    }

    /**
     * getFilter
     *
     * @return array
     */
    protected function getFilter(): array
    {
        if (! property_exists($this, 'filter'))
            return [];
        if (! is_array($this->filter))
            return [];

        foreach($this->filter as $q=>$filter)
        {
            $resolver = new OptionsResolver();

            $resolver->setRequired(
                [
                    'group_style',
                    'name',
                    'fields',
                    'label',
                ]
            );
            $resolver->setAllowedTypes('group_style',  'string');
            $resolver->setAllowedTypes('name',  'string');
            $resolver->setAllowedTypes('fields',  'array');
            $groupStyles = ['one_only', 'multiple'];
            if (! in_array($filter['group_style'], $groupStyles))
                throw new InvalidOptionsException(sprintf('The option "group_style" with value "%s" is expected to be of value "%s".',
                    $filter['group_style'],
                    implode('" or "', $groupStyles)));

            $resolver->resolve($filter);

            foreach($filter['fields'] as $f=>$field){
                $resolver = new OptionsResolver();
                $resolver->setRequired(
                    [
                        'name',
                        'value',
                        'field',
                        'label',
                    ]
                );
                $resolver->setAllowedTypes('name',  'string');
                $resolver->setAllowedTypes('value',  'array');
                $resolver->setAllowedTypes('label',  'string');
                $resolver->setAllowedTypes('field',  'string');

                $resolver->resolve($field);
            }
        }

        return $this->filter;
    }
}