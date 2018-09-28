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
 * Date: 25/09/2018
 * Time: 14:25
 */
namespace App\Manager;

use App\Entity\DayOfWeek;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use App\Entity\Timetable;
use App\Entity\TimetableDay;
use App\Entity\TimetableDayDate;
use App\Manager\Traits\EntityTrait;
use App\Util\StringHelper;

/**
 * Class TimetableManager
 * @package App\Manager
 */
class TimetableManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Timetable::class;

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return $this->getEntity()->canDelete();
    }

    /**
     * @var array
     */
    protected $tabs = [];

    /**
     * setTabs
     *
     * @return TimetableManager
     */
    public function setTabs(): TimetableManager
    {
        if (! empty($this->tabs))
            return $this;
        $terms = [];
        foreach ($this->getTerms()->getIterator() as $term) {
            $terms[] = [
                'name' => StringHelper::safeString($term->getName()),
                'label' => $term->getName(),
                'include' => 'Timetable/term_days.html.twig',
                'message' => 'timetableTermDaysMessage',
                'with' => ['term' => $term],
                'translation' => 'Timetable',
            ];


        $tabs = [
            [
                'name' => 'details',
                'label' => 'Details',
                'include' => 'Timetable/details.html.twig',
                'message' => 'timetableDetailsMessage',
                'translation' => 'Timetable',
            ],
            [
                'name' => 'days',
                'label' => 'Days',
                'include' => 'Timetable/days.html.twig',
                'message' => 'timetableDayMessage',
                'translation' => 'Timetable',
            ],
        ];

        }

        $this->tabs = array_merge($tabs, $terms);

        return $this;
    }

    /**
     * getTerms
     *
     * @return mixed
     */
    private function getTerms()
    {
        return $this->getEntity()->getSchoolYear()->getTerms();
    }

    /**
     * @var array
     */
    private $daysOfWeek;

    /**
     * getSchoolDays
     *
     * @return array
     * @throws \Exception
     */
    public function getSchoolDays(): array
    {
        if (empty($this->daysOfWeek)) {
            $w = $this->getRepository(DayOfWeek::class)->findBy(['schoolDay' => true], ['sequence' => 'ASC']);
            $this->daysOfWeek = [];
            foreach ($w as $day)
                $this->daysOfWeek[$day_of_week = date('N', strtotime($day->getName()))] = $day;
        }
        return $this->daysOfWeek;
    }

    /**
     * getAssignedDays
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    private function getAssignedDays(SchoolYearTerm $term): array
    {
        $results = $this->getRepository(TimetableDayDate::class)->createQueryBuilder('dd')
            ->select('dd,d')
            ->leftJoin('dd.timetableDay', 'd')
            ->where('d.timetable = :timetable')
            ->setParameter('timetable', $this->getEntity())
            ->andWhere('dd.date >= :start_date')
            ->andWhere('dd.date <= :last_date')
            ->setParameter('start_date', $term->getFirstDay())
            ->setParameter('last_date', $term->getLastDay())
            ->orderBy('dd.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        if (empty($results))
            $results = $this->createDayDates($term);

        $specialDays = $this->getRepository(SchoolYearSpecialDay::class)->createQueryBuilder('sd')
            ->andWhere('sd.date >= :start_date')
            ->andWhere('sd.date <= :last_date')
            ->setParameter('start_date', $term->getFirstDay())
            ->setParameter('last_date', $term->getLastDay())
            ->orderBy('sd.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $sd = [];
        foreach($specialDays as $w)
            $sd[$w->getDate()->format('Ymd')] = $w;

        foreach($results as $day)
            if (isset($sd[$day->getDate()->format('Ymd')]))
                $day->setSpecialDay($sd[$day->getDate()->format('Ymd')]);

        return $results;
    }

    /**
     * createDayDates
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    private function createDayDates(SchoolYearTerm $term): array
    {
        $results = [];

        $days = $this->getTimetableDays();

        $rotate = 0;

        $day = $term->getFirstDay();
        while ($day<=$term->getLastDay())
        {
            if ($rotate >= count($days))
                $rotate = 0;

            if (isset($this->getSchoolDays()[$day->format('N')]))
            {
                $dd = new TimetableDayDate();
                $dd->setDate(clone $day);
                $x = $day->format('N');

                $w = $rotate;
                $count = 0;
                dump([$this,$days]);
                while ($days[$rotate]->getNormalisedDayOfWeek() !== $x) {
                    $rotate++;
                    if ($rotate >= count($days))
                        $rotate = 0;
                };

                $dd->setTimetableDay($days[$rotate]);
                $dd->setStartRotation(false);
                $this->getEntityManager()->persist($dd);
                $results[] = $dd;
            }
            $day = date_add($day, date_interval_create_from_date_string('+1 Day'));
        }

        $this->getEntityManager()->flush();

        return $results;
    }

    /**
     * getWeeks
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    public function getWeeks(SchoolYearTerm $term): array
    {
        $firstMonday = $term->getSchoolYear()->getFirstDay();
        while($firstMonday->format('N') !== '1')
            $firstMonday = date_add($firstMonday, date_interval_create_from_date_string('+1 Day'));

        $weeks = [];

        $week = [];
        $q = 0;

        foreach($this->getAssignedDays($term) as $day)
        {
            if ($q > $day->getDayOfWeek())
            {
                $weeks[] = $week;
                $q = 0;
                $week = [];
            }
            if ($day->getDayOfWeek() >= $q)
            {
                $week[] = $day;
                $q = $day->getDayOfWeek();
            }
        }

        if (! empty($week))
            $weeks[] = $week;

        $w = [];
        foreach($weeks as $q=>$week)
            $w[intval(date_diff($firstMonday, $week[0]->getDate())->days / 7)] = $week;

        return $w;
    }

    /**
     * nextDayDate
     *
     * @param TimetableDayDate $dayDate
     * @return TimetableManager
     * @throws \Exception
     */
    public function nextDayDate(TimetableDayDate $dayDate): TimetableManager
    {
        $days = $this->getTimetableDays();

        $x = array_search($dayDate->getTimetableDay(), $days);

        $x++;
        if ($x >= count($days))
            $x=0;
        $dayDate->setTimetableDay($days[$x]);
        $this->getEntityManager()->persist($dayDate);
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * @var array
     */
    private $timetableDays;

    /**
     * getTimetableDays
     *
     * @return array
     * @throws \Exception
     */
    public function getTimetableDays(): array
    {
        if (empty($this->timetableDays))
            $this->timetableDays = $this->getRepository(TimetableDay::class)->findBy(['timetable' => $this->getEntity()], ['sequence' => 'ASC']);
        return $this->timetableDays;
    }
}