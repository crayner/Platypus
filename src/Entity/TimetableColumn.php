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
 * Time: 08:02
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class TimetableColumn
 * @package App\Entity
 */
class TimetableColumn
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
     * @return TimetableColumn
     */
    public function setId(?int $id): TimetableColumn
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
     * @return TimetableColumn
     */
    public function setName(?string $name): TimetableColumn
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
     * @return TimetableColumn
     */
    public function setNameShort(?string $nameShort): TimetableColumn
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $timetableColumnRows;

    /**
     * getTimetableColumnRows
     *
     * @return Collection
     */
    public function getTimetableColumnRows(): Collection
    {
        if (empty($this->timetableColumnRows))
            $this->timetableColumnRows = new ArrayCollection();

        if ($this->timetableColumnRows instanceof PersistentCollection)
            $this->timetableColumnRows->initialize();

        $iterator = $this->timetableColumnRows->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getTimeStart() < $b->getTimeStart()) ? -1 : 1;
        });

        $this->timetableColumnRows = new ArrayCollection(iterator_to_array($iterator, false));

        return $this->timetableColumnRows;
    }

    /**
     * @param Collection|null $timetableColumnRows
     * @return TimetableColumn
     */
    public function setTimetableColumnRows(?Collection $timetableColumnRows): TimetableColumn
    {
        $this->timetableColumnRows = $timetableColumnRows;
        return $this;
    }

    /**
     * addTimetableColumnRow
     *
     * @param TimetableColumnRow|null $row
     * @param bool $add
     * @return TimetableColumn
     */
    public function addTimetableColumnRow(?TimetableColumnRow $row, $add = true): TimetableColumn
    {
        if (empty($row) || $this->getTimetableColumnRows()->contains($row))
            return $this;

        if ($add)
            $row->setTimetableColumn($this, false);

        $this->timetableColumnRows->add($row);

        return $this;
    }

    /**
     * removeTimetableColumnRow
     *
     * @param TimetableColumnRow|null $row
     * @return TimetableColumn
     */
    public function removeTimetableColumnRow(?TimetableColumnRow $row): TimetableColumn
    {
        $this->getTimetableColumnRows()->removeElement($row);
        if (!empty($row))
            $row->setTimetableColumn(null, false);
        return $this;
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
     * @return TimetableColumn
     */
    public function setTimetableDays(?Collection $timetableDays): TimetableColumn
    {
        $this->timetableDays = $timetableDays;
        return $this;
    }

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        if ($this->getTimetableDays()->count() > 0)
            return false;
        return true;
    }

    public function __toString(): ?string
    {
        return $this->getName();
    }

    /**
     * @var DayOfWeek|null
     */
    private $dayOfWeek;

    /**
     * @return DayOfWeek|null
     */
    public function getDayOfWeek(): ?DayOfWeek
    {
        return $this->dayOfWeek;
    }

    /**
     * @param DayOfWeek|null $dayOfWeek
     * @return TimetableColumn
     */
    public function setDayOfWeek(?DayOfWeek $dayOfWeek): TimetableColumn
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    /**
     * getNormalisedDayOfWeek
     *
     * @return int|null
     */
    public function getNormalisedDayOfWeek(): ?int
    {
        if (empty($this->getDayOfWeek()))
            trigger_error(sprintf('The Timetable column "%s" must be linked to a day of the week.', $this->getName()), E_USER_ERROR);
        return $this->getDayOfWeek()->getNormalisedDayOfWeek();
    }
}