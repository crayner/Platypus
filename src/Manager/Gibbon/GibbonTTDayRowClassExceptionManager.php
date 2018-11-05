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
 * Date: 6/11/2018
 * Time: 07:28
 */
namespace App\Manager\Gibbon;

use App\Entity\TimetableDayRowClassException;

class GibbonTTDayRowClassExceptionManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableDayRowClassException::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTDayRowClassException';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonTTDayRowClass',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTDayRowClassExceptionID' => [
            'field' => 'id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonTTDayRowClassID' => [
            'field' => 'timetable_day_row_class_id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonPersonID' => [
            'field' => 'person_id',
            'functions' => [
                'integer' => null,
            ],
        ],
    ];
}