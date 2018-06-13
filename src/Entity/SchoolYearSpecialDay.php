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
 * Date: 12/06/2018
 * Time: 16:34
 */
namespace App\Entity;

use App\Entity\Extension\SchoolYearSpecialDayExtension;

class SchoolYearSpecialDay extends SchoolYearSpecialDayExtension
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
     * @return SchoolYearSpecialDay
     */
    public function setId(?int $id): SchoolYearSpecialDay
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
     * @return SchoolYearSpecialDay
     */
    public function setName(?string $name): SchoolYearSpecialDay
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var array
     */
    public static $typeList = [
        'school_closure',
        'timing_change',
    ];

    /**
     * @return array
     */
    public function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * @return null|string
     */
    public function getType(): string
    {
        return $this->type = in_array($this->type, $this->getTypeList()) ? $this->type : 'school_closure';
    }

    /**
     * @param null|string $type
     * @return SchoolYearSpecialDay
     */
    public function setType(?string $type): SchoolYearSpecialDay
    {
        $this->type = in_array($type, $this->getTypeList()) ? $type : 'school_closure';
        return $this;
    }

    /**
     * @var string|null
     */
    private $description;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return SchoolYearSpecialDay
     */
    public function setDescription(?string $description): SchoolYearSpecialDay
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return SchoolYearSpecialDay
     */
    public function setDate(\DateTime $date): SchoolYearSpecialDay
    {
        $this->date = $date;
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
     * @return SchoolYearSpecialDay
     */
    public function setSchoolOpen(?\DateTime $schoolOpen): SchoolYearSpecialDay
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
     * @return SchoolYearSpecialDay
     */
    public function setSchoolStart(?\DateTime $schoolStart): SchoolYearSpecialDay
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
     * @return SchoolYearSpecialDay
     */
    public function setSchoolEnd(?\DateTime $schoolEnd): SchoolYearSpecialDay
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
     * @return SchoolYearSpecialDay
     */
    public function setSchoolClose(?\DateTime $schoolClose): SchoolYearSpecialDay
    {
        $this->schoolClose = $schoolClose;
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
     * @param bool $add
     * @return SchoolYear
     */
    public function setSchoolYear(?SchoolYear $schoolYear, $add = true): SchoolYearSpecialDay
    {
        if ($add && $schoolYear)
            $schoolYear->addSpecialDay($this, false);

        $this->schoolYear = $schoolYear;
        return $this;
    }
}