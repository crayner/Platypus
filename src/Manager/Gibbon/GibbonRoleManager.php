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

use App\Entity\PersonRole;

class GibbonRoleManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        PersonRole::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonRole';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonRoleID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'category' => [
            'field' => 'category',
            'functions' => [
                'lowercase' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 20,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 4,
            ],
        ],
        'description' => [
            'field' => 'description',
            'functions' => [
                'length' => 60,
            ],
        ],
        'type' => [
            'field' => 'role_type',
            'functions' => [
                'lowercase' => '',
            ],
        ],
        'canLoginRole' => [
            'field' => 'can_login',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'futureYearsLogin' => [
            'field' => 'future_years_login',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'pastYearsLogin' => [
            'field' => 'past_years_login',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'restriction' => [
            'field' => 'restriction',
            'functions' => [
                'lowercase' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonPerson';
}