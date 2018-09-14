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
 * Time: 10:27
 */

namespace App\Manager\Gibbon;


use App\Entity\Facility;

class GibbonFacilityManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Facility::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonSpace';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonSpaceID' => [
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
        'type' => [
            'field' => 'space_type',
            'functions' => [
                'length' => 30,
            ],
        ],
        'comment' => [
            'field' => 'comment',
        ],
        'capacity' => [
            'field' => 'capacity',
            'functions' => [
                'integer' => '',
                'nullable' => '',
            ],
        ],
        'computerStudent' => [
            'field' => 'student_computers',
            'functions' => [
                'integer' => '',
                'default' => 0,
            ],
        ],
        'computer' => [
            'field' => 'computer',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'iwb' => [
            'field' => 'iwb',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'projector' => [
            'field' => 'projector',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'tv' => [
            'field' => 'tv',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'dvd' => [
            'field' => 'dvd',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'hifi' => [
            'field' => 'hifi',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'speakers' => [
            'field' => 'speakers',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'phoneInternal' => [
            'field' => 'phone_internal',
            'functions' => [
                'length' => 5,
                'nullable' => '',
            ],
        ],
        'phoneExternal' => [
            'field' => 'phone_external',
            'functions' => [
                'length' => 20,
                'nullable' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonFileExtension';
}
