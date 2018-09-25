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
 * Date: 20/09/2018
 * Time: 21:38
 */
namespace App\Pagination;

use App\Entity\Course;
use App\Util\SchoolYearHelper;

/**
 * Class CoursePagination
 * @package App\Pagination
 */
class CoursePagination extends PaginationReactManager
{
    /**
     * @var string
     */
    protected $name = 'Course';

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
    protected $entityName = Course::class;

    /**
     * @var string
     */
    protected $transDomain = 'Course';


    /**
     * @var array|null
     */
    protected $searchDefinition = [
        'name',
        'nameShort',
        'department',
    ];

    /**
     * @var array
     */
    protected $sortByList = [
        'Abbreviation' => [
            'nameShort',
            'name',
        ],
        'Name' => [
            'name',
            'nameShort',
        ],
        'Department' => [
            'department',
            'name',
            'nameShort',
        ],
        'Class Count' => [
            'classes',
            'name',
            'nameShort',
        ],
    ];

    /**
     * getAllResults
     *
     * @return array
     */
    public function getAllResults(): array
    {
        $result =  $this->getRepository(Course::class)->createQueryBuilder('c')
            ->orderBy('c.name')
            ->select('c.nameShort, c.id, c.name, d.name as department, count(cl.id) as classes')
            ->leftJoin('c.department', 'd')
            ->leftJoin('c.classes', 'cl')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->groupBy('c.id')
            ->getQuery()
            ->getArrayResult();

        return $result;
    }

    protected $columnDefinitions = [
        'c.nameShort' => [
            'label' => 'Abbrev.',
            'name' => 'nameShort',
        ],
        'c.name' => [
            'label' => 'Name',
            'name' => 'name',
            'size' => 4,
        ],
        'c.id' => [
            'name' => 'id',
            'display' => false,
            'label' => false,
        ],
        'department' => [
            'name' => 'department',
            'label' => 'Learning Area',
        ],
        'classes' => [
            'name' => 'classes',
            'label' => 'Classes',
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
                'label' => 'Delete Course',
                'url' => '/course/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'classMerge' => 'btn-sm',
            ],
            [
                'label' => 'Edit Course',
                'url' => '/course/__id__/edit/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
                'classMerge' => 'btn-sm',
            ],
        ],
    ];
}