<?php
namespace App\Manager;

/**
 * Pagination Interfaces
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
	 * @return mixed
	 */
	public function buildQuery($count = false);
}