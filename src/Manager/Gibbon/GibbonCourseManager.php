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
 * Time: 21:13
 */

namespace App\Manager\Gibbon;


use App\Entity\Course;

class GibbonCourseManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Course::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonCourse';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonYearGroup',
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonCourseClass';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonCourseID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonSchoolYearID' => [
            'field' => 'school_year_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonDepartmentID' => [
            'field' => 'department_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 45,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 6,
            ],
        ],
        'description' => [
            'field' => 'description',
            'functions' => [
                'nullable' => 6,
            ],
        ],
        'map' => [
            'field' => 'map',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'orderBy' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonYearGroupIDList' => [
            'field' => '',
            'functions' => [
                'commaList' => null,
            ],
            'joinTable' => [
                'name' => 'course_year_group',
                'join' => 'course_id',
                'inverse' => 'year_group_id',
                'call' => ['function' => 'getYearGroups', 'options' => ''],
            ],
        ],
    ];

    /**
     * getYearGroups
     *
     * @param $value
     * @return array
     */
    public function getYearGroups($value): array
    {
        return $value;
    }
}
