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
 * Date: 26/09/2018
 * Time: 11:46
 */
namespace App\Pagination;

use App\Entity\TimetableColumn;

/**
 * Class TimetableColumnPagination
 * @package App\Pagination
 */
class TimetableColumnPagination extends PaginationReactManager
{
    /**
     * @var string
     */
    protected $name = 'TimetableColumn';

    /**
     * @var string
     */
    protected $alias = 'c';

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var string
     */
    protected $entityName = TimetableColumn::class;

    /**
     * @var string
     */
    protected $transDomain = 'Timetable';


    /**
     * @var array|null
     */
    protected $searchDefinition = [
        'name',
        'nameShort',
    ];

    /**
     * @var array
     */
    protected $sortByList = [];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'c.name' => [
            'label' => 'Name',
            'name' => 'name',
            'size' => 3,
        ],
        'c.nameShort' => [
            'label' => 'Abbrev.',
            'name' => 'nameShort',
            'size' => 3,
        ],
        'c.id' => [
            'name' => 'id',
            'display' => false,
            'label' => false,
        ],
        'dayOfWeek' => [
            'name' => 'dayOfWeek',
            'label' => 'Day of Week',
            'class' => 'text-center',
        ],
        'rows' => [
            'name' => 'rows',
            'label' => 'Rows',
            'class' => 'text-center',
        ],
    ];

    /**
     * @var array
     */
    protected $actions = [
        'size' => 2,
        'buttons' => [
            [
                'label' => 'Delete Timetable Column',
                'url' => '/timetable/column/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'classMerge' => 'btn-sm',
            ],
            [
                'label' => 'Edit Timetable Column',
                'url' => '/timetable/column/__id__/edit/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
                'classMerge' => 'btn-sm',
            ],
        ],
    ];

    /**
     * @var array
     */
    protected $headerDefinition = [
        'title' => 'Timetable Column List',
        'paragraph' => 'In Busybee a column is a holder for the structure of a day. A number of columns can be defined, and these can be tied to particular timetable days in the timetable interface.',
        'buttons' => [
            [
                'label' => 'Add Timetable Column',
                'url' => '/timetable/column/Add/edit/',
                'type' => 'add',
                'response_type' => 'redirect',
                'classMerge' => 'btn-sm',
            ],
        ],
    ];

    /**
     * getAllResults
     *
     * @return array
     */
    public function getAllResults(): array
    {
        $result = $this->getRepository()->createQueryBuilder('c')
            ->select('c.nameShort, c.id, c.name, COUNT(r.id) as rows, d.nameShort as dayOfWeek')
            ->leftJoin('c.timetableColumnRows', 'r')
            ->leftJoin('c.dayOfWeek', 'd')
            ->orderBy('d.sequence')
            ->groupBy('c.id')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }
}
