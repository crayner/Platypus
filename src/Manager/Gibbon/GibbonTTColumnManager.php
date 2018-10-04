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
 * Time: 08:20
 */
namespace App\Manager\Gibbon;

use App\Entity\DayOfWeek;
use App\Entity\TimetableColumn;

/**
 * Class GibbonTTColumnManager
 * @package App\Manager\Gibbon
 */
class GibbonTTColumnManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        TimetableColumn::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonTTColumn';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonDaysOfWeek',
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonTTColumnID' => [
            'field' => 'id',
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
                'length' => 12,
            ],
        ],
    ];

    /**
     * postRecord
     *
     * @param $entityName
     * @param $newData
     * @param $records
     * @return array
     */
    public function postRecord($entityName, $newData, $records)
    {
        $this->getDaysoftheWeek();
        $x = preg_replace('/[^a-zA-z]/','',$newData['name']);
        if (isset($this->getDaysoftheWeek()[$x]))
        {
            $newData['day_of_week_id'] = $this->getDaysoftheWeek()[$x]['id'];
            $records[] = $newData;
            return $records;
        }
        foreach($this->getDaysoftheWeek() as $w)
        {
            if (mb_strpos($w['name'], $x) !== false)
            {
                $newData['day_of_week_id'] = $w['id'];
                $records[] = $newData;
                return $records;
            }
            if (mb_strpos($w['nameShort'], $x) !== false)
            {
                $newData['day_of_week_id'] = $w['id'];
                $records[] = $newData;
                return $records;
            }
        }
        $this->getLogger()->warning(sprintf('The column %s was not linked to a day of the week.', $newData['name']));
        $newData['day_of_week_id'] = null;
        $records[] = $newData;
        return $records;
    }

    /**
     * @var array
     */
    private $daysoftheWeek;

    /**
     * @return array
     */
    private function getDaysoftheWeek(): array
    {
        if (! empty($this->daysoftheWeek))
            return $this->daysoftheWeek;

        $this->daysoftheWeek = $this->getObjectManager()->getRepository(DayOfWeek::class)->createQueryBuilder('d', 'd.nameShort')
            ->getQuery()
            ->getArrayResult();

        foreach($this->daysoftheWeek as $q=>$w)
            $this->daysoftheWeek[$q]['normDay'] = date('N', strtotime($q));

        return $this->daysoftheWeek;
    }

}
