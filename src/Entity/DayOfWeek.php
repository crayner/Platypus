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
 * Date: 11/06/2018
 * Time: 16:39
 */
namespace App\Entity;

class DayOfWeek
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
     * @return DayOfWeek
     */
    public function setId(?int $id): DayOfWeek
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
     * @return DayOfWeek
     */
    public function setName(?string $name): DayOfWeek
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
     * @return DayOfWeek
     */
    public function setNameShort(?string $nameShort): DayOfWeek
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequenceNumber;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int|null $sequenceNumber
     * @return DayOfWeek
     */
    public function setSequenceNumber(?int $sequenceNumber): DayOfWeek
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @var boolean
     */
    private $schoolDay;

    /**
     * @return bool
     */
    public function isSchoolDay(): bool
    {
        return $this->schoolDay ? true : false;
    }

    /**
     * @param bool $schoolDay
     * @return DayOfWeek
     */
    public function setSchoolDay(bool $schoolDay): DayOfWeek
    {
        $this->schoolDay = $schoolDay? true : false;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $schoolOpen;

    /**
     * @return \DateTime|null
     */
    public function getSchoolOpen(): ?\DateTime
    {
        return $this->schoolOpen;
    }

    /**
     * @param \DateTime $schoolOpen
     * @return DayOfWeek
     */
    public function setSchoolOpen(?\DateTime $schoolOpen): DayOfWeek
    {
        $this->schoolOpen = $schoolOpen;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $schoolStart;

    /**
     * @return \DateTime|null
     */
    public function getSchoolStart(): ?\DateTime
    {
        return $this->schoolStart;
    }

    /**
     * @param \DateTime $schoolStart
     * @return DayOfWeek
     */
    public function setSchoolStart(?\DateTime $schoolStart): DayOfWeek
    {
        $this->schoolStart = $schoolStart;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $schoolEnd;

    /**
     * @return \DateTime|null
     */
    public function getSchoolEnd(): ?\DateTime
    {
        return $this->schoolEnd;
    }

    /**
     * @param \DateTime $schoolEnd
     * @return DayOfWeek
     */
    public function setSchoolEnd(?\DateTime $schoolEnd): DayOfWeek
    {
        $this->schoolEnd = $schoolEnd;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $schoolClose;

    /**
     * @return \DateTime|null
     */
    public function getSchoolClose(): ?\DateTime
    {
        return $this->schoolClose;
    }

    /**
     * @param \DateTime $schoolClose
     * @return DayOfWeek
     */
    public function setSchoolClose(?\DateTime $schoolClose): DayOfWeek
    {
        $this->schoolClose = $schoolClose;
        return $this;
    }
}