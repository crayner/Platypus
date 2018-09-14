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
 * Time: 11:03
 */
namespace App\Manager\Gibbon;

use App\Entity\INDescriptor;

/**
 * Class GibbonINDescriptorManager
 * @package App\Manager\Gibbon
 */
class GibbonINDescriptorManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        INDescriptor::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonINDescriptor';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonINDescriptorID' => [
            'field' => 'id',
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
                'length' => 5,
            ],
        ],
        'description' => [
            'field' => 'description',
        ],
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}
