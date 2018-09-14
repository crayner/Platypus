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

use App\Entity\Department;

class GibbonDepartmentManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Department::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonDepartment';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonDepartmentID' => [
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
                'length' => 4,
            ],
        ],
        'subjectListing' => [
            'field' => 'subject_listing',
            'functions' => [
                'length' => 255,
            ],
        ],
        'type' => [
            'field' => 'department_type',
            'functions' => [
                'length' => 20,
                'enum' => '',
            ],
        ],
        'blurb' => [
            'field' => 'blurb',
        ],
        'logo' => [
            'field' => 'logo',
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}