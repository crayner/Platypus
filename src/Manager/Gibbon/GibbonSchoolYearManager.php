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
     * @var string
     */
    protected $nextGibbonName = 'gibbonSchoolYearSpecialDay';
}