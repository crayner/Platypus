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
 * Date: 5/11/2018
 * Time: 18:07
 */
namespace App\Entity;

/**
 * Class TimetableDayRowClass
 * @package App\Entity
 */
class TimetableDayRowClass
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
     * @return TimetableDayRowClass
     */
    public function setId(?int $id): TimetableDayRowClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var TimetableColumnRow|null
     */
    private $timetableColumnRow;

    /**
     * @return TimetableColumnRow|null
     */
    public function getTimetableColumnRow(): ?TimetableColumnRow
    {
        return $this->timetableColumnRow;
    }

    /**
     * @param TimetableColumnRow|null $timetableColumnRow
     * @return TimetableDayRowClass
     */
    public function setTimetableColumnRow(?TimetableColumnRow $timetableColumnRow): TimetableDayRowClass
    {
        $this->timetableColumnRow = $timetableColumnRow;
        return $this;
    }

    /**
     * @var TimetableDay|null
     */
    private $timetableDay;

    /**
     * @return TimetableDay|null
     */
    public function getTimetableDay(): ?TimetableDay
    {
        return $this->timetableDay;
    }

    /**
     * @param TimetableDay|null $timetableDay
     * @return TimetableDayRowClass
     */
    public function setTimetableDay(?TimetableDay $timetableDay): TimetableDayRowClass
    {
        $this->timetableDay = $timetableDay;
        return $this;
    }

    /**
     * @var CourseClass|null
     */
    private $courseClass;

    /**
     * @return CourseClass|null
     */
    public function getCourseClass(): ?CourseClass
    {
        return $this->courseClass;
    }

    /**
     * @param CourseClass|null $courseClass
     * @return TimetableDayRowClass
     */
    public function setCourseClass(?CourseClass $courseClass): TimetableDayRowClass
    {
        $this->courseClass = $courseClass;
        return $this;
    }

    /**
     * @var Facility|null
     */
    private $facility;

    /**
     * @return Facility|null
     */
    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    /**
     * @param Facility|null $facility
     * @return TimetableDayRowClass
     */
    public function setFacility(?Facility $facility): TimetableDayRowClass
    {
        $this->facility = $facility;
        return $this;
    }
}