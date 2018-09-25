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
 * Time: 08:20
 */
namespace App\Manager\Gibbon;

use App\Entity\TimetableColumn;

/**
 * Class GibbonTTColumnManager
 * @package App\Manager\Gibbon
 */
class GibbonTTColumnManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableColumn::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTColumn';

    /**
     * @var array
     */
    protected $requireBefore = [
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTColumnID' => [
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
                'length' => 12,
            ],
        ],
    ];
}
