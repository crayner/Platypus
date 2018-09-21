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
 * Date: 21/09/2018
 * Time: 12:16
 */
namespace App\Manager\Gibbon;

use App\Entity\CourseClassPerson;

/**
 * Class GibbonCourseClassPersonManager
 * @package App\Manager\Gibbon
 */
class GibbonCourseClassPersonManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        CourseClassPerson::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonCourseClassPerson';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonCourseClassPersonID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonCourseClassID' => [
            'field' => 'class_id',
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
        'role' => [
            'field' => 'role',
            'functions' => [
                'length' => 20,
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'reportable' => [
            'field' => 'reportable',
            'functions' => [
                'boolean' => null,
            ],
        ],
    ];
}
