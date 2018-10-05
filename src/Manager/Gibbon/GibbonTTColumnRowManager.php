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
 * Date: 26/09/2018
 * Time: 13:08
 */
namespace App\Manager\Gibbon;

use App\Entity\TimetableColumn;
use App\Entity\TimetableColumnRow;

/**
 * Class GibbonTTColumnRowManager
 * @package App\Manager\Gibbon
 */
class GibbonTTColumnRowManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableColumnRow::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTColumnRow';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonTTColumn',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTColumnRowID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonTTColumnID' => [
            'field' => 'timetable_column_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 12,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 4,
            ],
        ],
        'type' => [
            'field' => 'column_row_type',
            'functions' => [
                'safeString' => ['removeChars' => ['-']],
            ],
        ],
        'timeStart' => [
            'field' => 'time_start',
            'functions' => [
                'time' => null,
            ],
        ],
        'timeEnd' => [
            'field' => 'time_end',
            'functions' => [
                'time' => null,
            ],
        ],
    ];

    /**
     * postRecord
     *
     * @param $entityName
     * @param $newData
     * @param $records
     */
    public function postRecord($entityName, $newData, $records)
    {

        $column = $this->getObjectManager()->getRepository(TimetableColumn::class)->find($newData['timetable_column_id']);

        if (date('H:i', strtotime($newData['time_start'])) < $column->getDayOfWeek()->getSchoolStart()->format('H:i'))
        {
            $dow = $column->getDayOfWeek();
            $dow->setSchoolStart(new \DateTime($newData['time_start']));
            if ($dow->getSchoolOpen()->format('H:i') > $dow->getSchoolStart()->format('H:i'))
                $dow->setSchoolOpen($dow->getSchoolStart());

            $this->getObjectManager()->persist($dow);
            $this->getObjectManager()->flush();
        }

        if (date('H:i', strtotime($newData['time_end'])) > $column->getDayOfWeek()->getSchoolEnd()->format('H:i'))
        {
            $dow = $column->getDayOfWeek();
            $dow->setSchoolEnd(new \DateTime($newData['time_end']));
            if ($dow->getSchoolClose()->format('H:i') < $dow->getSchoolEnd()->format('H:i'))
                $dow->setSchoolClose($dow->getSchoolEnd());

            $this->getObjectManager()->persist($dow);
            $this->getObjectManager()->flush();
        }
        $records[] = $newData;
        return $records;
    }
}
