<?php
namespace App\Pagination;

use App\Entity\Facility;

class FacilityPagination extends PaginationManager
{
	/**
	 * @var string
	 */
	protected $paginationName = 'Facility';

	/**
	 * @var string
	 */
	protected $alias = 's';

	/**
	 * @var array
	 */
	protected $sortByList = [
		'facility.sort.name' => [
			's.name' => 'ASC',
			's.type' => 'ASC',
		],
		'facility.sort.type' => [
			's.type' => 'ASC',
			's.name' => 'ASC',
		],
		'facility.sort.capacity' => [
			's.capacity' => 'DESC',
			's.name' => 'ASC',
			's.type' => 'ASC',
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
		's.name',
		's.type',

	];

	/**
	 * @var array
	 */
	protected $select = [
		's.name',
		's.type',
		's.capacity',
		's.id',
	];

	/**
	 * @var string
	 */
	protected $repositoryName = Facility::class;

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
	 * @return    query
	 */
	public function buildQuery($count = false)
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

		return $this->getQuery();
	}
}