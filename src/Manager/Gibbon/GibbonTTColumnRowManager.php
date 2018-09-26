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
 * Time: 13:08
 */
namespace App\Manager\Gibbon;

use App\Entity\TimetableColumnRow;

/**
 * Class GibbonTTColumnRowManager
 * @package App\Manager\Gibbon
 */
class GibbonTTColumnRowManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableColumnRow::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTColumnRow';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonTTColumn',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTColumnRowID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonTTColumnID' => [
            'field' => 'timetable_column_id',
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
                'length' => 4,
            ],
        ],
        'type' => [
            'field' => 'column_row_type',
            'functions' => [
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'timeStart' => [
            'field' => 'time_start',
            'functions' => [
                'time' => null,
            ],
        ],
        'timeEnd' => [
            'field' => 'time_end',
            'functions' => [
                'time' => null,
            ],
        ],
    ];
}
