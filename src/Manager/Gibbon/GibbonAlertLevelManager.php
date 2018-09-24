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
 * Date: 23/09/2018
 * Time: 17:50
 */

namespace App\Manager\Gibbon;


use App\Entity\AlertLevel;

class GibbonAlertLevelManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        AlertLevel::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonAlertLevel';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonAlertLevelID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 50,
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
                'length' => 20,
            ],
        ],
        'colorBG' => [
            'field' => 'colour_bg',
            'functions' => [
                'length' => 20,
            ],
        ],
        'description' => [
            'field' => 'description',
        ],
    ];
}
