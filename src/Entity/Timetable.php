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
 * Time: 11:39
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Timetable
 * @package App\Entity
 */
class Timetable
{
    /**
     * Timetable constructor.
     */
    public function __construct()
    {
        $this->setNameShortDisplay('day_of_the_week');
        $this->setActive(true);
    }

    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getNameShortDisplayList(): array
    {
        return self::$nameShortDisplayList;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Timetable
     */
    public function setId(?int $id): Timetable
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Timetable
     */
    public function setName(?string $name): Timetable
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nameShort;

    /**
     * @return null|string
     */
    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    /**
     * @param null|string $nameShort
     * @return Timetable
     */
    public function setNameShort(?string $nameShort): Timetable
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nameShortDisplay;

    /**
     * @var array
     */
    private static $nameShortDisplayList = [
        'day_of_the_week',
        'timetable_day_short_name',
        ''
    ];

    /**
     * getNameShortDisplay
     *
     * @return null|string
     */
    public function getNameShortDisplay(): ?string
    {
        return $this->nameShortDisplay = in_array($this->nameShortDisplay, self::$nameShortDisplayList) ? $this->nameShortDisplay : 'timetableDay_of_the_week' ;
    }

    /**
     * setNameShortDisplay
     *
     * @param null|string $nameShortDisplay
     * @return Timetable
     */
    public function setNameShortDisplay(?string $nameShortDisplay): Timetable
    {
        $this->nameShortDisplay = in_array($nameShortDisplay, self::$nameShortDisplayList) ? $nameShortDisplay : 'timetableDay_of_the_week' ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $active;

    /**
     * isActive
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active = $this->active ? true : false ;
    }

    /**
     * setActive
     *
     * @param bool|null $active
     * @return Timetable
     */
    public function setActive(?bool $active): Timetable
    {
        $this->active = $active ? true : false ;
        return $this;
    }

    /**
     * @var SchoolYear|null
     */
    private $schoolYear;

    /**
     * @return SchoolYear|null
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * @param SchoolYear|null $schoolYear
     * @return Timetable
     */
    public function setSchoolYear(?SchoolYear $schoolYear): Timetable
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * @var Collection
     */
    private $yearGroups;

    /**
     * @return Collection
     */
    public function getYearGroups(): Collection
    {
        if (empty($this->yearGroups))
            $this->yearGroups = new ArrayCollection();

        if ($this->yearGroups instanceof PersistentCollection)
            $this->yearGroups->initialize();

        return $this->yearGroups;
    }

    /**
     * setYearGroups
     *
     * @param null|Collection $yearGroups
     * @return Timetable
     */
    public function setYearGroups(?Collection $yearGroups): Timetable
    {
        $this->yearGroups = $yearGroups;
        return $this;
    }

    /**
     * addYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return Timetable
     */
    public function addYearGroup(?YearGroup $yearGroup): Timetable
    {
        if (empty($yearGroup) || $this->getYearGroups()->contains($yearGroup))
            return $this;

        $this->yearGroups->add($yearGroup);

        return $this;
    }

    /**
     * removeYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return Timetable
     */
    public function removeYearGroup(?YearGroup $yearGroup): Timetable
    {
        $this->getYearGroups()->removeElement($yearGroup);
        return $this;
    }

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        if ($this->isActive())
            return false;
        return true;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @var Collection|null
     */
    private $timetableDays;

    /**
     * getTimetableDays
     *
     * @return Collection
     */
    public function getTimetableDays(): Collection
    {
        if (empty($this->timetableDays))
            $this->timetableDays = new ArrayCollection();

        if ($this->timetableDays instanceof PersistentCollection)
            $this->timetableDays->initialize();

        return $this->timetableDays;
    }

    /**
     * @param Collection|null $timetableDays
     * @return Timetable
     */
    public function setTimetableDays(?Collection $timetableDays): Timetable
    {
        $this->timetableDays = $timetableDays;
        return $this;
    }

    /**
     * addTimetableDay
     *
     * @param TimetableDay|null $timetableDay
     * @param bool $add
     * @return Timetable
     */
    public function addTimetableDay(?TimetableDay $timetableDay, $add = true): Timetable
    {
        if (empty($timetableDay) || $this->getTimetableDays()->contains($timetableDay))
            return $this;

        if ($add)
            $timetableDay->setTimetable($this, false);

        $this->timetableDays->add($timetableDay);

        return $this;
    }

    /**
     * removeTimetableDay
     *
     * @param TimetableDay|null $timetableDay
     * @return Timetable
     */
    public function removeTimetableDay(?TimetableDay $timetableDay): Timetable
    {
        if (! empty($timetableDay))
            $timetableDay->setTimetable(null,false);
        $this->getTimetableDays()->removeElement($timetableDay);

        return $this;
    }
}