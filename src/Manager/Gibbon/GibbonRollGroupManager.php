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
 * Date: 20/09/2018
 * Time: 13:45
 */
namespace App\Manager\Gibbon;

use App\Entity\RollGroup;

/**
 * Class GibbonRollGroupManager
 * @package App\Manager\Gibbon
 */
class GibbonRollGroupManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonRollGroup';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonStudentEnrolment';

    /**
     * @var array
     */
    protected $entityName = [
        RollGroup::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonRollGroupID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonSchoolYearID' => [
            'field' => 'school_year_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonRollGroupIDNext' => [
            'field' => 'next_roll_group_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonSpaceID' => [
            'field' => 'facility_id',
            'functions' => [
                'integer' => '',
                'nullable' => null,
            ],
        ],
        'attendance' => [
            'field' => 'attendance',
            'functions' => [
                'boolean' => '',
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
                'length' => 5,
            ],
        ],
        'gibbonPersonIDTutor' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_tutor',
                'join' => 'roll_group_id',
                'inverse' => 'tutor_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'gibbonPersonIDTutor3' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_tutor',
                'join' => 'roll_group_id',
                'inverse' => 'tutor_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'gibbonPersonIDTutor2' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_tutor',
                'join' => 'roll_group_id',
                'inverse' => 'tutor_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'gibbonPersonIDEA' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_assistant',
                'join' => 'roll_group_id',
                'inverse' => 'tutor_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'gibbonPersonIDEA3' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_assistant',
                'join' => 'roll_group_id',
                'inverse' => 'assistant_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'gibbonPersonIDEA2' => [
            'field' => '',
            'joinTable' => [
                'name' => 'roll_group_assistant',
                'join' => 'roll_group_id',
                'inverse' => 'assistant_id',
                'call' => ['function' =>'getTutors', 'options' => ''],
            ],
        ],
        'website' => [
            'field' => 'website',
            'functions' => [
                'length' => 255,
            ],
        ],
    ];

    /**
     * getTutors
     *
     * @param $value
     * @return array
     */
    public function getTutors($value): array
    {
        return [$value];
    }
}
