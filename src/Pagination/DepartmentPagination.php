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
 * Date: 23/06/2018
 * Time: 18:07
 */
namespace App\Pagination;

use App\Entity\Department;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DepartmentPagination
 * @package App\Pagination
 */
class DepartmentPagination extends PaginationManager
{
    /**
     * @var string
     */
    protected $paginationName = 'Department';

    /**
     * @var string
     */
    protected $alias = 'd';

    /**
     * @var array
     */
    protected $sortByList = [
        'department.sort.name' => [
            'd.name' => 'ASC',
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
        'd.name',
        'd.nameShort',
    ];

    /**
     * @var array
     */
    protected $select = [
        'd.name',
        'd.type',
        'd.nameShort',
        'd.id',
    ];

    /**
     * @var array
     */
    protected $join = [
/*        'r.facility' => [
            'alias' => 'f',
            'type' => 'leftJoin',
        ],
        'r.schoolYear' => [
            'alias' => 'y',
            'type' => 'leftJoin',
        ],*/
    ];

    /**
     * @var string
     * Use array or defaults to entity
     */
    protected $castAs = 'array';

    /**
     * @var string
     */
    protected $repositoryName = Department::class;

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

        return $this->getQuery();
    }
}
