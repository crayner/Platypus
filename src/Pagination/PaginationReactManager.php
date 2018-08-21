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

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * PaginationReactManager constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager)
    {
        $this->request = $request->getCurrentRequest();
        $this->entityManager = $entityManager;
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
        $props['sortOptions'] = $this->getSortList();
        $props['sortByList'] = $this->getSortByList();
        $props['results'] = $this->getAllResults();
        $props['offset'] = $this->getOffset();
        $props['search'] = $this->getSearch() ?: '';
        $props['sort'] = $this->getSort() ?: '';
        $props['limit'] = $this->getLimit();
        $props['columnDefinitions'] = $this->getColumnDefinitions();
        $props['headerDefinition'] = $this->getHeaderDefinition();

        $props['translations'] = [];
        $props['translations'][] = 'pagination.search.label';
        $props['translations'][] = 'pagination.search.placeholder';
        $props['translations'][] = 'search';
        $props['translations'][] = 'pagination.sort.label';
        $props['translations'][] = 'pagination.limit.label';
        $props['translations'][] = 'save';
        $props['translations'][] = 'previous';
        $props['translations'][] = 'next';
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

        if (empty($this->select)  || !is_array($this->select)) return $this;

        foreach ($this->select as $name)
        {
            if (is_string($name)) {
                if ($selectBegin){
                    $this->query->select($name);
                    $selectBegin = false;
                } else
                    $this->query->addSelect($name);

                $searchConcat = $this->addSearchConcat($name);
            } elseif (is_array($name))
            {
                $k      = key($name);
                if ($k == '0')
                    $k = 'entity';
                $concat = new Query\Expr\Func('CONCAT', $name[$k]);
                $concat .= ' AS ' . $k;
                $concat = str_replace(',', ',\' \',', $concat);
                if ($selectBegin){
                    $this->query->select($concat);
                    $selectBegin = false;
                } else
                $this->query->addSelect($concat);
                $searchConcat = $this->addSearchConcat($name[$k]);
            }
        }
        if (! empty($searchConcat))
        {
            $concat = new Query\Expr\Func('CONCAT', $searchConcat);
            $concat .= ' AS SearchString';
            $concat = str_replace('|', '\'|\'', $concat);
            $this->query->addSelect($concat);
        }

        return $this;
    }

    /**
     * @var array|null
     */
    private $searchConcat;

    /**
     * addSearchConcat
     *
     * @param string $name
     * @return array|null
     */
    private function addSearchConcat(string $name): ?array
    {
        if ($this->getSearchDefinition() === null)
            return null;

        if (empty($this->searchConcat))
            $this->searchConcat = [];

        if (strpos(strtolower($name), ' as ') !== false)
            $name = substr($name, 0, strpos(strtolower($name), ' as '));

        if (! in_array($name, $this->searchDefinition))
            return $this->searchConcat;

        $this->searchConcat[] = $name;
        $this->searchConcat[] = '|';

        return $this->searchConcat;
    }

    /**
     * getSearchDefinition
     *
     * @return array|null
     */
    private function getSearchDefinition(): ?array
    {
        return (property_exists($this, 'searchDefinition') && is_array($this->searchDefinition) && ! empty($this->searchDefinition)) ? $this->searchDefinition : null ;

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
            $this->sessionData = $this->getSession()->get('pagination')[$this->getName()] ?: [];
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
     * @param mixed $search
     * @return PaginationInterface
     */
    public function setSearch($search): PaginationInterface
    {
        $this->search = $search;
        return $this;
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

    private function getSpecificTranslations()
    {
        if (property_exists($this, 'specificTranslations'))
            $specificTranslations = $this->getSpecificTranslations;
        else
            $specificTranslations = [];

        foreach($this->getSortList() as $name)
            $specificTranslations[] = $name;

        foreach($this->getColumnDefinitions() as $definition)
            if ($definition['label'] !== false)
                $specificTranslations[] = $definition['label'];

        $specificTranslations[] = $this->getHeaderDefinition()['title'];

        if ($this->getHeaderDefinition()['paragraph'] !== false)
            $specificTranslations[] = $this->getHeaderDefinition()['paragraph'];

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
        if (! property_exists($this, 'columnDefinitions'))
            throw new \Exception('The Column definitions are missing.');
        if (! is_array($this->columnDefinitions))
            throw new \Exception('The Column definitions is not an array.');

        $resolver = new OptionsResolver();
        $resolver->setRequired($this->select);
        $resolver->resolve($this->columnDefinitions);

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
                    'style' => 'text',
                    'options' => [],
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
        if (! property_exists($this, 'headerDefinition'))
            throw new \Exception('The header definition is missing.');
        if (! is_array($this->headerDefinition))
            throw new \Exception('The header definition is not an array.');

        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'title',
            ]
        );
        $resolver->setDefaults(
            [
                'paragraph' => false,
            ]
        );
        $headerDefinition = $resolver->resolve($this->headerDefinition);

        return $headerDefinition;
    }

    /**
     * @var array
     */
    protected $headerDefinition = [
        'title' => 'person.pagination.title',
    ];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'p.photo' => [
            'label' => 'person.photo.label',
            'name' => 'photo',
            'style' => 'photo',
            'options' => [
                'width' => '75',
                'default' => 'build/static/images/DefaultPerson.png'
            ],
        ],
        'p.title' => [
            'label' => 'person.title.label',
            'name' => 'title',
        ],
        'p.surname' => [
            'label' => 'person.surname.label',
            'name' => 'surname',
        ],
        'p.firstName' => [
            'label' => 'person.firstName.label',
            'name' => 'firstName',
        ],
        'p.email' => [
            'label' => 'person.email.label',
            'name' => 'email',
        ],
        'p.id' => [
            'label' => false,
            'display' => false,
            'name' => 'id',
        ],
        'u.id AS userId' => [
            'label' => false,
            'display' => false,
            'name' => 'userId',
        ],
    ];

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
        return $this->sort = $this->sort ?: isset($this->getSessionData()['sort']) ? $this->getSessionData()['sort'] : '';
    }

}