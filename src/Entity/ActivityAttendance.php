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

class ActivityAttendance
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
     * @return ActivityAttendance
     */
    public function setId(?int $id): ActivityAttendance
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $attendance;

    /**
     * @return null|string
     */
    public function getAttendance(): ?string
    {
        return $this->attendance;
    }

    /**
     * @param null|string $attendance
     * @return ActivityAttendance
     */
    public function setAttendance(?string $attendance): ActivityAttendance
    {
        $this->attendance = $attendance;
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
     * @return ActivityAttendance
     */
    public function setDate(?\DateTime $date): ActivityAttendance
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $timestampTaken;

    /**
     * @return \DateTime
     */
    public function getTimestampTaken(): \DateTime
    {
        return $this->timestampTaken;
    }

    /**
     * @param \DateTime $timestampTaken
     * @return ActivityAttendance
     */
    public function setTimestampTaken(\DateTime $timestampTaken): ActivityAttendance
    {
        $this->timestampTaken = $timestampTaken;
        return $this;
    }

    /**
     * ActivityAttendance constructor.
     */
    public function __construct()
    {
        $this->setTimestampTaken(new \DateTime('now'));
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
     * @return ActivityAttendance
     */
    public function setActivity(?Activity $activity): ActivityAttendance
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $personTaker;

    /**
     * @return Person|null
     */
    public function getPersonTaker(): ?Person
    {
        return $this->personTaker;
    }

    /**
     * @param Person|null $personTaker
     * @return ActivityAttendance
     */
    public function setPersonTaker(?Person $personTaker): ActivityAttendance
    {
        $this->personTaker = $personTaker;
        return $this;
    }
}