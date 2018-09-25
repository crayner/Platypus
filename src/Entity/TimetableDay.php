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
 * Time: 07:56
 */
namespace App\Entity;

/**
 * Class TimetableDay
 * @package App\Entity
 */
class TimetableDay
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
     * @return TimetableDay
     */
    public function setId(?int $id): TimetableDay
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
     * @return TimetableDay
     */
    public function setName(?string $name): TimetableDay
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
     * @return TimetableDay
     */
    public function setNameShort(?string $nameShort): TimetableDay
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $colour;

    /**
     * @return null|string
     */
    public function getColour(): ?string
    {
        return $this->colour;
    }

    /**
     * @param null|string $colour
     * @return TimetableDay
     */
    public function setColour(?string $colour): TimetableDay
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * @var string|null
     */
    private $fontColour;

    /**
     * @return null|string
     */
    public function getFontColour(): ?string
    {
        return $this->fontColour;
    }

    /**
     * @param null|string $fontColour
     * @return TimetableDay
     */
    public function setFontColour(?string $fontColour): TimetableDay
    {
        $this->fontColour = $fontColour;
        return $this;
    }

    /**
     * @var Timetable|null
     */
    private $timetable;

    /**
     * @return Timetable|null
     */
    public function getTimetable(): ?Timetable
    {
        return $this->timetable;
    }

    /**
     * @param Timetable|null $timetable
     * @return TimetableDay
     */
    public function setTimetable(?Timetable $timetable): TimetableDay
    {
        $this->timetable = $timetable;
        return $this;
    }

    /**
     * @var TimetableColumn|null
     */
    private $timetableColumn;

    /**
     * @return TimetableColumn|null
     */
    public function getTimetableColumn(): ?TimetableColumn
    {
        return $this->timetableColumn;
    }

    /**
     * @param TimetableColumn|null $timetableColumn
     * @return TimetableDay
     */
    public function setTimetableColumn(?TimetableColumn $timetableColumn): TimetableDay
    {
        $this->timetableColumn = $timetableColumn;
        return $this;
    }
}