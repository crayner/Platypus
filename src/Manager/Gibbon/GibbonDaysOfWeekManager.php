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
 * Date: 13/09/2018
 * Time: 15:12
 */
namespace App\Manager\Gibbon;

use App\Entity\DayOfWeek;

class GibbonDaysOfWeekManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        DayOfWeek::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonDaysOfWeek';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonDaysOfWeekID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 10,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 4,
            ],
        ],
        'schoolDay' => [
            'field' => 'school_day',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],

        ],
        'schoolOpen' => [
            'field' => 'school_open',
            'functions' => [
                'time' => '',
            ],
        ],
        'schoolStart' => [
            'field' => 'school_start',
            'functions' => [
                'time' => '',
            ],
        ],
        'schoolEnd' => [
            'field' => 'school_end',
            'functions' => [
                'time' => '',
            ],
        ],
        'schoolClose' => [
            'field' => 'school_close',
            'functions' => [
                'time' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}