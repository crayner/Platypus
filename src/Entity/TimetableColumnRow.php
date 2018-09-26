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
 * Time: 12:26
 */
namespace App\Entity;

/**
 * Class TimetableColumnRow
 * @package App\Entity
 */
class TimetableColumnRow
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
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
     * @return TimetableColumnRow
     */
    public function setId(?int $id): TimetableColumnRow
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
     * @return TimetableColumnRow
     */
    public function setName(?string $name): TimetableColumnRow
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
     * @return TimetableColumnRow
     */
    public function setNameShort(?string $nameShort): TimetableColumnRow
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $timeStart;

    /**
     * @return \DateTime|null
     */
    public function getTimeStart(): ?\DateTime
    {
        return $this->timeStart;
    }

    /**
     * @param \DateTime|null $timeStart
     * @return TimetableColumnRow
     */
    public function setTimeStart(?\DateTime $timeStart): TimetableColumnRow
    {
        $this->timeStart = $timeStart;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $timeEnd;

    /**
     * @return \DateTime|null
     */
    public function getTimeEnd(): ?\DateTime
    {
        return $this->timeEnd;
    }

    /**
     * @param \DateTime|null $timeEnd
     * @return TimetableColumnRow
     */
    public function setTimeEnd(?\DateTime $timeEnd): TimetableColumnRow
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    private static $typeList = ['lesson','pastoral','sport','break','service','other'];

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return TimetableColumnRow
     */
    public function setType(?string $type): TimetableColumnRow
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @var TimetableColumn|null
     */
    public $timetableColumn;

    /**
     * @return TimetableColumn|null
     */
    public function getTimetableColumn(): ?TimetableColumn
    {
        return $this->timetableColumn;
    }

    /**
     * setTimetableColumn
     *
     * @param TimetableColumn|null $timetableColumn
     * @param bool $add
     * @return TimetableColumnRow
     */
    public function setTimetableColumn(?TimetableColumn $timetableColumn, $add = true): TimetableColumnRow
    {
        $this->timetableColumn = $timetableColumn;
        if ($add && ! empty($timetableColumn))
            $timetableColumn->addTimetableColumnRow($this, false);

        return $this;
    }

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return false;
    }

    /**
     * __toString
     *
     * @return null|string
     */
    public function __toString(): ?string
    {
        return $this->getTimetableColumn()->getName() . ' ' . $this->getName();
    }
}