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
    protected $nextGibbonName = '';

    /**
     * @var array
     */
    protected $entityName = [
        Staff::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [];

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
            'field' => 'person_id',
            'functions' => [
                'integer' => '',
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
        'countryOfOrigin' => [
            'field' => 'country_of_origin',
            'functions' => [
                'length' => 80,
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
}
