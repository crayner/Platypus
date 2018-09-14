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
 * Date: 14/09/2018
 * Time: 10:47
 */
namespace App\Manager\Gibbon;

use App\Entity\House;

/**
 * Class GibbonHouseManager
 * @package App\Manager\Gibbon
 */
class GibbonHouseManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        House::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonHouse';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonHouseID' => [
            'field' => 'id',
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
                'length' => 4,
            ],
        ],
        'logo' => [
            'field' => 'logo',
            'functions' => [
                'length' => 255,
                'nullable' => ''
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}
