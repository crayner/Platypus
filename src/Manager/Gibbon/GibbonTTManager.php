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
 * Time: 11:50
 */

namespace App\Manager\Gibbon;


use App\Entity\Timetable;

class GibbonTTManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Timetable::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTT';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonYearGroup',
        'gibbonSchoolYear',
    ];

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonTTID' => [
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
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 30,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 12,
            ],
        ],
        'nameShortDisplay' => [
            'field' => 'name_short_display',
            'functions' => [
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'active' => [
            'field' => 'active',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'gibbonYearGroupIDList' => [
            'field' => '',
            'functions' => [
                'commaList' => null,
            ],
            'joinTable' => [
                'name' => 'timetable_year_group',
                'join' => 'timetable_id',
                'inverse' => 'year_group_id',
                'call' => ['function' =>'getYearGroups', 'options' => ''],
            ],
        ],
    ];

    /**
     * getYearGroups
     *
     * @param $value
     * @return mixed
     */
    public function getYearGroups($value)
    {
        foreach($value as $q=>$w)
            $value[$q] = intval($w);

        return $value;
    }
}
