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

use App\Entity\TimetableDay;

/**
 * Class GibbonTTDayManager
 * @package App\Manager\Gibbon
 */
class GibbonTTDayManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableDay::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTDay';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonTT',
        'gibbonTTColumn',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTID' => [
            'field' => 'timetable_id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonTTDayID' => [
            'field' => 'id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonTTColumnID' => [
            'field' => 'timetable_column_id',
            'functions' => [
                'integer' => null,
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
        'color' => [
            'field' => 'colour',
            'functions' => [
                'colour' => null,
            ],
        ],
        'fontColor' => [
            'field' => 'font_colour',
            'functions' => [
                'colour' => null,
            ],
        ],
    ];

    /**
     * @var int
     */
    private $sequence = 1;

    /**
     * postRecord
     *
     * @param $entityName
     * @param $newData
     * @param $records
     * @return array
     */
    public function postRecord($entityName, $newData, $records)
    {
        $newData['sequence'] = $this->sequence++;
        $records[] = $newData;
        return $records;
    }
}
