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
 * Date: 29/06/2018
 * Time: 11:33
 */
namespace App\Pagination;

use App\Entity\ExternalAssessment;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ExternalAssessmentPagination
 * @package App\Pagination
 */
class ExternalAssessmentPagination extends PaginationManager
{
    /**
     * @var string
     */
    protected $paginationName = 'ExternalAssessment';

    /**
     * @var string
     */
    protected $alias = 'e';

    /**
     * @var array
     */
    protected $sortByList = [
        'external_assessment.sort.name' => [
            'e.name' => 'ASC',
        ],
    ];

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var array
     */
    protected $searchList = [
        'e.name',
        'e.nameShort',
        'e.description',
    ];

    /**
     * @var array
     */
    protected $select = [
        'e.name',
        'e.description',
        'e.id',
        'e.allowFileUpload',
        'e.active',
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
    protected $repositoryName = ExternalAssessment::class;

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