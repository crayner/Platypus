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
 * Date: 18/09/2018
 * Time: 08:12
 */
namespace App\Manager\Gibbon;

use App\Entity\Person;
use App\Entity\Staff;

class GibbonStaffManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonStaff';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonDepartmentStaff';

    /**
     * @var array
     */
    protected $entityName = [
        Staff::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [
        Person::class => true,
        'person' => true,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonStaffID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonPersonID' => [
            'field' => '',
            'functions' => [
                'integer' => null,
            ],
            // This one is flipped, so be careful...
            'joinTable' => [
                'name' => 'person',
                'join' => 'staff_id',
                'inverse' => 'id',
                'call' => ['function' =>'getStaffID', 'options' => ''],
                'update' => 'id',
            ],
        ],
        'type' => [
            'field' => 'staff_type',
            'functions' => [
                'length' => 20,
                'safeString' => null,
            ],
        ],
        'jobTitle' => [
            'field' => 'job_title',
            'functions' => [
                'length' => 100,
            ],
        ],
        'smartWorkflowHelp' => [
            'field' => 'smart_workflow_help',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'firstAidQualified' => [
            'field' => 'first_aid_qualified',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'firstAidExpiry' => [
            'field' => 'first_aid_expiry',
            'functions' => [
                'date' => null,
                'nullable' => null,
            ],
        ],
        'countryOfOrigin' => [
            'field' => 'country_of_origin',
            'functions' => [
                'length' => 80,
            ],
        ],
        'initials' => [
            'field' => 'initials',
            'functions' => [
                'length' => 4,
                'nullable' => null,
            ],
        ],
        'qualifications' => [
            'field' => 'qualifications',
        ],
        'biography' => [
            'field' => 'biography',
        ],
        'biographicalGrouping' => [
            'field' => 'biographical_grouping',
            'functions' => [
                'length' => 100,
            ],
        ],
        'biographicalGroupingPriority' => [
            'field' => 'biographical_grouping_priority',
            'functions' => [
                'integer' => null,
            ],
        ],
    ];

    /**
     * getStaffID
     *
     * @param $value
     * @param $options
     * @return array
     */
    public function getStaffID($value, $options)
    {
        return [$value];
    }
}
