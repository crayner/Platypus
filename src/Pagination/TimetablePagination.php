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
 * Date: 25/09/2018
 * Time: 12:17
 */
namespace App\Pagination;

use App\Entity\Timetable;
use App\Util\SchoolYearHelper;

/**
 * Class TimetablePagination
 * @package App\Pagination
 */
class TimetablePagination extends PaginationReactManager
{
    /**
     * @var string
     */
    protected $name = 'Timetable';

    /**
     * @var string
     */
    protected $alias = 't';

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var string
     */
    protected $entityName = Timetable::class;

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
        'yearGroups',
    ];

    /**
     * @var array
     */
    protected $sortByList = [
        'Abbreviation' => [
            'schoolYear',
            'nameShort',
            'name',
        ],
        'Name' => [
            'schoolYear',
            'name',
            'nameShort',
        ],
    ];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        't.name' => [
            'label' => 'Name',
            'name' => 'name',
            'size' => 2,
        ],
        't.nameShort' => [
            'label' => 'Abbrev.',
            'name' => 'nameShort',
            'class' => 'text-center',
        ],
        't.id' => [
            'name' => 'id',
            'display' => false,
            'label' => false,
        ],
        'schoolYear' => [
            'name' => 'schoolYear',
            'display' => false,
            'label' => false,
        ],
        'yearGroups' => [
            'label' => 'Year Groups',
            'name' => 'yearGroups',
            'size' => 4,
            'style' => 'array',
            'options' => ['join' => ', ',],
        ],
        't.active' => [
            'label' => 'Active',
            'name' => 'active',
            'style' => 'boolean',
            'options' => ['classMerge' => 'btn-sm', 'on' => ['icon' => ['far','check-circle']], 'off' => ['icon' => ['far','times-circle']]],
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
                'label' => 'Delete Timetable',
                'url' => '/timetable/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'classMerge' => 'btn-sm',
            ],
            [
                'label' => 'Edit Timetable',
                'url' => '/timetable/__id__/edit/',
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
        'buttons' => [
            [
                'label' => 'Add Timetable',
                'url' => '/timetable/Add/edit/',
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
        $result = $this->getRepository()->createQueryBuilder('t')
            ->select('t.nameShort, t.id, t.name, s.sequence AS schoolYear, t.active, g.nameShort as yearGroup')
            ->leftJoin('t.schoolYear', 's')
            ->leftJoin('t.yearGroups', 'g')
            ->orderBy('t.id', 'ASC')
            ->addOrderBy('g.sequence', 'ASC')
            ->where('s = :schoolYear')
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getArrayResult();

        $tt = [];
        $id = 0;
        foreach($result as $w)
        {
            if ($id !== $w['id'])
            {
                $w['yearGroups'] = [$w['yearGroup']];
                unset($w['yearGroup']);
                $id = $w['id'];
                $tt[$id] = $w;
            } else {
                $tt[$id]['yearGroups'][] = $w['yearGroup'];
            }
        }
        sort($tt);

        return $tt;
    }
}
