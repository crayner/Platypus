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
 * Date: 24/09/2018
 * Time: 13:22
 */

namespace App\Manager\Gibbon;


use App\Entity\YearGroup;

class GibbonYearGroupManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonYearGroup';

    /**
     * @var array
     */
    protected $entityName = [
        YearGroup::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonYearGroupID' => [
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
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => null,
            ],
        ],
        'gibbonPersonIDHOY' => [
            'field' => 'head_of_year_id',
            'functions' => [
                'integer' => null,
                'nullable' => null,
            ],
        ],
    ];
}
