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

class ActivitySlot
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
     * @return ActivitySlot
     */
    public function setId(?int $id): ActivitySlot
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $locationExternal;

    /**
     * @return null|string
     */
    public function getLocationExternal(): ?string
    {
        return $this->locationExternal;
    }

    /**
     * @param null|string $locationExternal
     * @return ActivitySlot
     */
    public function setLocationExternal(?string $locationExternal): ActivitySlot
    {
        $this->locationExternal = $locationExternal;
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
     * @return ActivitySlot
     */
    public function setTimeStart(?\DateTime $timeStart): ActivitySlot
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
     * @return ActivitySlot
     */
    public function setTimeEnd(?\DateTime $timeEnd): ActivitySlot
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @var Activity|null
     */
    private $activity;

    /**
     * @return Activity|null
     */
    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity|null $activity
     * @return ActivitySlot
     */
    public function setActivity(?Activity $activity): ActivitySlot
    {
        $this->activity = $activity;
        return $this;
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
     * @return ActivitySlot
     */
    public function setDayOfWeek(?DayOfWeek $dayOfWeek): ActivitySlot
    {
        $this->dayOfWeek = $dayOfWeek;
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
     * @return ActivitySlot
     */
    public function setFacility(?Facility $facility): ActivitySlot
    {
        $this->facility = $facility;
        return $this;
    }
}