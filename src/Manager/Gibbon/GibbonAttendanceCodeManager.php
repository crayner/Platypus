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
 * Time: 17:20
 */
namespace App\Manager\Gibbon;


use App\Entity\AttendanceCode;

class GibbonAttendanceCodeManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        AttendanceCode::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonAttendanceCode';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonAlertLevel';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonAttendanceCodeID' => [
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
                'length' => 30,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 4,
            ],
        ],
        'type' => [
            'field' => 'attendance_code_type',
            'functions' => [
                'length' => 16,
                'safeString' => null,
            ],
        ],
        'direction' => [
            'field' => 'direction',
            'functions' => [
                'length' => 8,
                'safeString' => null,
            ],
        ],
        'scope' => [
            'field' => 'scope',
            'functions' => [
                'length' => 16,
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'active' => [
            'field' => 'active',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'reportable' => [
            'field' => 'reportable',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'future' => [
            'field' => 'future',
            'functions' => [
                'boolean' => null,
            ],
        ],
        'gibbonRoleIDAll' => [
            'field' => '',
            'functions' => [
                'commaList' => null,
            ],
            'joinTable' => [
                'name' => 'attendance_code_person_role',
                'join' => 'attendance_code_id',
                'inverse' => 'person_role_id',
                'call' => ['function' =>'getPersonRoles', 'options' => ''],
            ],
        ],
    ];

    /**
     * getPersonRoles
     *
     * @param $value
     * @return array
     */
    public function getPersonRoles($value): array
    {
        foreach($value as $q=>$w)
            $value[$q] = intval($w);
        return $value;
    }
}
