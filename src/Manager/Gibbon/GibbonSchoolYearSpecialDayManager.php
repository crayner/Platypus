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
use Doctrine\Common\Persistence\ObjectManager;

class GibbonSchoolYearSpecialDayManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        SchoolYearSpecialDay::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonSchoolYearSpecialDay';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonSchoolYear',
    ];

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonSchoolYearSpecialDayID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonSchoolYearTermID' => [
            'field' => '',
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 20,
            ],
        ],
        'description' => [
            'field' => 'description',
            'functions' => [
                'length' => 255,
            ],
        ],
        'type' => [
            'field' => 'special_day_type',
            'functions' => [
                'length' => 16,
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'date' => [
            'field' => 'day_date',
            'functions' => [
                'date' => '',
            ],
        ],
        'schoolOpen' => [
            'field' => 'school_open',
            'functions' => [
                'time' => '',
                'nullable' => null,
            ],
        ],
        'schoolStart' => [
            'field' => 'school_start',
            'functions' => [
                'time' => '',
                'nullable' => null,
            ],
        ],
        'schoolEnd' => [
            'field' => 'school_end',
            'functions' => [
                'time' => '',
                'nullable' => null,
            ],
        ],
        'schoolClose' => [
            'field' => 'school_close',
            'functions' => [
                'time' => '',
                'nullable' => null,
            ],
        ],
    ];

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @param array $records
     * @param ObjectManager $manager
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function postRecord(string $entityName, array $newData, array $records): array
    {
        // need to add School Year indicator.
        $schoolYear = $this->getObjectManager()->getRepository(SchoolYear::class)->createQueryBuilder('y')
            ->select('y.id')
            ->where('y.firstDay <= :f_date')
            ->andWhere('y.lastDay >= :l_date')
            ->setParameter('f_date', new \DateTime($newData['day_date']))
            ->setParameter('l_date', new \DateTime($newData['day_date']))
            ->getQuery()
            ->getOneOrNullResult();
        if (! empty($schoolYear))
            $newData['school_year_id'] = $schoolYear['id'];
        else
            $newData = [];

        $records[] = $newData;

        return $records;
    }
}