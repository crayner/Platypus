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
 * Date: 02/08/2018
 * Time: 12:15
 */
namespace App\Pagination;

use App\Entity\Scale;
use App\Entity\StringReplacement;
use Doctrine\ORM\QueryBuilder;

/**
 * Class StringReplacementPagination
 * @package App\Pagination
 */
class StringReplacementPagination extends PaginationManager
{
    /**
     * @var string
     */
    protected $paginationName = 'StringReplacement';

    /**
     * @var string
     */
    protected $alias = 's';

    /**
     * @var array
     */
    protected $sortByList = [
        'string.original.sort' => [
            's.original' => 'ASC',
        ],
        'string.replacement.sort' => [
            's.replacement' => 'ASC',
            's.original' => 'ASC',
        ],
        'string.priority.sort' => [
            's.priority' => 'DESC',
            's.original' => 'ASC',
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
        's.original',
        's.replacement',
        's.replaceMode',
    ];

    /**
     * @var array
     */
    protected $select = [
        's.original',
        's.replacement',
        's.replaceMode',
        's.caseSensitive',
        's.priority',
        's.id',
    ];

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var string
     * Use array or defaults to entity
     */
    protected $castAs = 'array';

    /**
     * @var string
     */
    protected $repositoryName = StringReplacement::class;

    /**
     * @var string
     */
    protected $transDomain = 'System';

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
