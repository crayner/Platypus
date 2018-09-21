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
 * Date: 21/09/2018
 * Time: 09:33
 */
namespace App\Manager\Gibbon;

use App\Entity\CourseClass;

/**
 * Class GibbonCourseClassManager
 * @package App\Manager\Gibbon
 */
class GibbonCourseClassManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        CourseClass::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonCourseClass';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonCourseID' => [
            'field' => 'course_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonCourseClassID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonScaleIDTarget' => [
            'field' => 'scale_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 12,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 5,
            ],
        ],
        'reportable' => [
            'field' => 'reportable',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'attendance' => [
            'field' => 'attendance',
            'functions' => [
                'boolean' => null,
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
