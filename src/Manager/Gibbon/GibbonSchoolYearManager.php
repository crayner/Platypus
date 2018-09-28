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

use App\Entity\SchoolYear;

class GibbonSchoolYearManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        SchoolYear::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonSchoolYear';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonSchoolYearID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 9,
            ],
        ],
        'status' => [
            'field' => 'status',
            'functions' => [
                'length' => 10,
                'lowercase' => '',
            ],
        ],
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],
        ],
        'firstDay' => [
            'field' => 'first_day',
            'functions' => [
                'date' => '',
            ],
        ],
        'lastDay' => [
            'field' => 'last_day',
            'functions' => [
                'date' => '',
            ],
        ],
    ];

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @param array $records
     * @return array
     */
    public function postRecord(string $entityName, array $newData, array $records)
    {
        //Test if full year defined
        $start = new \DateTime($newData['first_day']);
        $end = new \DateTime($newData['last_day']);

        $diff = date_diff($end,$start);
        if ($diff->y !== 1 || $diff->m !== 0 || $diff->d !== 0) {

            //Test if north or south hemisphere year
            if(mb_substr($newData['first_day'], 0, 4) === mb_substr($newData['last_day'], 0, 4))
            {
                // Southern
                $newData['first_day'] = mb_substr($newData['first_day'], 0, 4) . '-01-01';
                $newData['last_day'] = mb_substr($newData['first_day'], 0, 4) . '-12-31';
            } else {
                //Northern
                $newData['first_day'] = mb_substr($newData['first_day'], 0, 4) . '-07-01';
                $newData['last_day'] = mb_substr($newData['last_day'], 0, 4) . '-06-30';
            }
        }

        $records[] = $newData;
        return $records;
    }
}