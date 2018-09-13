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

use App\Entity\SchoolYear;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use Doctrine\Common\Persistence\ObjectManager;

class GibbonSchoolYearTermManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        SchoolYearTerm::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonSchoolYearTerm';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonSchoolYearTermID' => [
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
        'firstDay' => [
            'field' => 'first_day',
            'functions' => [
                'date' => '',
            ],
        ],
        'lastDay' => [
            'field' => 'last_day',
            'functions' => [
                'date' => '',
            ],
        ],
        'sequenceNumber' => [
            'field' => '',
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}