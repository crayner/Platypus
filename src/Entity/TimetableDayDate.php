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
 * Date: 27/09/2018
 * Time: 10:23
 */

namespace App\Entity;


use App\Repository\TimetableDayRepository;

class TimetableDayDate
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return TimetableDayDate
     */
    public function setId(?int $id): TimetableDayDate
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $date;

    /**
     * @return \DateTime|null
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $date
     * @return TimetableDayDate
     */
    public function setDate(?\DateTime $date): TimetableDayDate
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @var TimetableDay|null
     */
    private $timetableDay;

    /**
     * getTimetableDay
     *
     * @param TimetableDayRepository|null $repository
     * @return TimetableDay|null
     */
    public function getTimetableDay(?TimetableDayRepository $repository = null): ?TimetableDay
    {
        if (empty($repository) || $this->getOffset() === 0 || empty($this->timetableDay))
            return $this->timetableDay;

        $days = $repository->findAll(['timetable' => $this->timetableDay->getTimetable()], ['sequence']);
        while ($days[0] !== $this->timetableDay)
            $days[] = array_shift($days);
        for($i=0; $i<=$this->getOffset(); $i++)
            $day = array_shift($days);

        return $day;
    }

    /**
     * @param TimetableDay|null $timetableDay
     * @return TimetableDayDate
     */
    public function setTimetableDay(?TimetableDay $timetableDay): TimetableDayDate
    {
        $this->timetableDay = $timetableDay;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $dayOfWeek;

    /**
     * @return int|null
     */
    public function getDayOfWeek(): ?int
    {
        return $this->dayOfWeek = $this->getDate()->format('N');
    }

    /**
     * @var SchoolYearSpecialDay|null
     */
    private $specialDay;

    /**
     * @return SchoolYearSpecialDay|null
     */
    public function getSpecialDay(): ?SchoolYearSpecialDay
    {
        return $this->specialDay;
    }

    /**
     * @param SchoolYearSpecialDay|null $specialDay
     * @return TimetableDayDate
     */
    public function setSpecialDay(?SchoolYearSpecialDay $specialDay): TimetableDayDate
    {
        $this->specialDay = $specialDay;
        return $this;
    }

    /**
     * getType
     *
     * @return string
     */
    public function getType(): string
    {
        if (! empty($this->getSpecialDay()))
            return $this->getSpecialDay()->getType();
        return '';
    }

    /**
     * @var integer
     */
    private $offset;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset = is_int($this->offset) ? $this->offset : 0;
    }

    /**
     * @param int $offset
     * @return TimetableDayDate
     */
    public function setOffset(int $offset): TimetableDayDate
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * incrementOffset
     *
     * @param int $max
     * @return TimetableDayDate
     */
    public function incrementOffset(int $max): TimetableDayDate
    {
        $this->getOffset();
        $this->offset++;
        if ($this->offset >= $max)
            $this->offset = 0;
        return $this;
    }
}