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
 * Time: 17:10
 */
namespace App\Pagination;

use App\Entity\NotificationEvent;
use Doctrine\ORM\QueryBuilder;

/**
 * Class NotificationEventPagination
 * @package App\Pagination
 */
class NotificationEventPagination extends PaginationManager
{
    /**
     * @var string
     */
    protected $paginationName = 'NotificationEvent';

    /**
     * @var string
     */
    protected $alias = 'n';

    /**
     * @var array
     */
    protected $sortByList = [
        'string.original.sort' => [
            'm.name' => 'ASC',
            'n.event' => 'ASC',
        ],
    ];

    /**
     * @var int
     */
    protected $limit = 100;

    /**
     * @var array
     */
    protected $searchList = [];

    /**
     * @var array
     */
    protected $select = [
        'm.name as moduleName',
        'n.event',
        'n.active',
        'n.id',
        'COUNT(l.id) as listenerCount'
    ];

    /**
     * @var array
     */
    protected $join = [
        'n.module' => [
            'type' => 'leftJoin',
            'alias' => 'm',
        ],
        'n.notificationListeners' => [
            'type' => 'leftJoin',
            'alias' => 'l',
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
    protected $repositoryName = NotificationEvent::class;

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

        $this->getQuery()
            ->andWhere('m.active = :active')
            ->setParameter('active', true)
            ->groupBy('n.id');

        return $this->getQuery();
    }
}
