<?php
namespace App\Pagination;

use App\Entity\RollGroup;
use App\Util\SchoolYearHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class RollGroupPagination
 * @package App\Pagination
 */
class RollGroupPagination extends PaginationManager
{
	/**
	 * @var string
	 */
	protected $paginationName = 'RollGroup';

	/**
	 * @var string
	 */
	protected $alias = 'r';

	/**
	 * @var array
	 */
	protected $sortByList = [
		'roll.sort.name' => [
			'r.name' => 'ASC',
		],
	];

	/**
	 * @var int
	 */
	protected $limit = 50;

	/**
	 * @var array
	 */
	protected $searchList = [
		'r.name',
        'r.nameShort',
        'r.website',
        'f.name',
	];

	/**
	 * @var array
	 */
	protected $select = [
		'r.name',
		'r.nameShort',
        'r.id',
        'f.name as facilityName',
        'r.website'
	];

    /**
     * @var array
     */
	protected $join = [
        'r.facility' => [
            'alias' => 'f',
            'type' => 'leftJoin',
        ],
        'r.schoolYear' => [
            'alias' => 'y',
            'type' => 'leftJoin',
        ],
    ];

    /**
     * @var string
     * Use array or defaults to entity
     */
    protected $castAs = 'array';

	/**
	 * @var string
	 */
	protected $repositoryName = RollGroup::class;

	/**
	 * @var string
	 */
	protected $transDomain = 'School';

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
		$this->initiateQuery($count);
		if ($count)
			$this
				->setQueryJoin()
				->setSearchWhere();
		else
			$this
				->setQuerySelect()
				->setQueryJoin()
				->setOrderBy()
				->setSearchWhere();

        $this->getQuery()
            ->andWhere('y = :year')
            ->setParameter('year', SchoolYearHelper::getCurrentSchoolYear())
        ;

		return $this->getQuery();
	}

    /**
     * RollGroupPagination constructor.
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param FormFactoryInterface $formFactory
     * @param SchoolYearHelper $helper
     */
    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, RequestStack $requestStack, FormFactoryInterface $formFactory, SchoolYearHelper $helper)
    {
        parent::__construct($entityManager, $router, $requestStack, $formFactory);
    }
}
