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
 * Time: 08:21
 */
namespace App\Manager\Gibbon;

use App\Entity\TimetableDayRowClass;

/**
 * Class GibbonTTDayManager
 * @package App\Manager\Gibbon
 */
class GibbonTTDayRowClassManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableDayRowClass::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTDayRowClass';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonTT',
        'gibbonTTColumn',
        'gibbonTTDay',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTDayRowClassID' => [
            'field' => 'id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonTTColumnRowID' => [
            'field' => 'timetable_column_row_id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonTTDayID' => [
            'field' => 'timetable_day_id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonCourseClassID' => [
            'field' => 'course_class_id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonSpaceID' => [
            'field' => 'facility_id',
            'functions' => [
                'integer' => null,
            ],
        ],
    ];
}
