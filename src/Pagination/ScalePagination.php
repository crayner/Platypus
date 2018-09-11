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
 * Date: 26/06/2018
 * Time: 16:02
 */
namespace App\Pagination;
use App\Entity\Scale;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ScalePagination
 * @package App\Pagination
 */
class ScalePagination extends PaginationManager
{
    /**
     * @var string
     */
    protected $paginationName = 'Scale';

    /**
     * @var string
     */
    protected $alias = 's';

    /**
     * @var array
     */
    protected $sortByList = [
        'scale.name.sort' => [
            's.name' => 'ASC',
        ],
        'scale.name_short.sort' => [
            's.nameShort' => 'ASC',
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
        's.nameShort',
        's.usage',
    ];

    /**
     * @var array
     */
    protected $select = [
        's.name',
        's.nameShort',
        's.id',
        's.active',
        's.numeric',
        's.usage',
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
    protected $repositoryName = Scale::class;

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
