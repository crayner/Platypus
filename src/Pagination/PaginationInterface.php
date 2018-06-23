<?php
namespace App\Pagination;
use Doctrine\ORM\QueryBuilder;

/**
 * Pagination Interface
 *
 * @version    25th October 2016
 * @since      25th October 2016
 * @author     Craig Rayner
 */
interface PaginationInterface
{
	/**
	 * Build Query
	 *
	 * @param bool $count
	 *
	 * @return QueryBuilder
	 */
	public function buildQuery($count = false): QueryBuilder;
}