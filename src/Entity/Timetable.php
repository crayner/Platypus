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
        return $this->nameShortDisplay = in_array($this->nameShortDisplay, self::$nameShortDisplayList) ? $this->nameShortDisplay : 'day_of_the_week' ;
    }

    /**
     * setNameShortDisplay
     *
     * @param null|string $nameShortDisplay
     * @return Timetable
     */
    public function setNameShortDisplay(?string $nameShortDisplay): Timetable
    {
        $this->nameShortDisplay = in_array($nameShortDisplay, self::$nameShortDisplayList) ? $nameShortDisplay : 'day_of_the_week' ;
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
    private $days;

    /**
     * getDays
     *
     * @return Collection
     */
    public function getDays(): Collection
    {
        if (empty($this->days))
            $this->days = new ArrayCollection();

        if ($this->days instanceof PersistentCollection)
            $this->days->initialize();

        return $this->days;
    }

    /**
     * @param Collection|null $days
     * @return Timetable
     */
    public function setDays(?Collection $days): Timetable
    {
        $this->days = $days;
        return $this;
    }

    /**
     * addDay
     *
     * @param TimetableDay|null $day
     * @param bool $add
     * @return Timetable
     */
    public function addDay(?TimetableDay $day, $add = true): Timetable
    {
        if (empty($day) || $this->getDays()->contains($day))
            return $this;

        if ($add)
            $day->setTimetable($this, false);

        $this->days->add($day);

        return $this;
    }

    /**
     * removeDay
     *
     * @param TimetableDay|null $day
     * @return Timetable
     */
    public function removeDay(?TimetableDay $day): Timetable
    {
        if (! empty($day))
            $day->setTimetable(null,false);
        $this->getDays()->removeElement($day);

        return $this;
    }
}