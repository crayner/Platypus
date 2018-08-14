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

class AlarmConfirm
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
     * @return AlarmConfirm
     */
    public function setId(?int $id): AlarmConfirm
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $person;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return AlarmConfirm
     */
    public function setPerson(?Person $person): AlarmConfirm
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp ?: new \DateTime('now');
    }

    /**
     * @param \DateTime|null $timestamp
     * @return AlarmConfirm
     */
    public function setTimestamp(?\DateTime $timestamp): AlarmConfirm
    {
        $this->timestamp = $timestamp ?: new \DateTime('now');
        return $this;
    }

    /**
     * @var Alarm|null
     */
    private $alarm;

    /**
     * @return Alarm|null
     */
    public function getAlarm(): ?Alarm
    {
        return $this->alarm;
    }

    /**
     * @param Alarm|null $alarm
     * @return AlarmConfirm
     */
    public function setAlarm(?Alarm $alarm): AlarmConfirm
    {
        $this->alarm = $alarm;
        return $this;
    }

    /**
     * AlarmConfirm constructor.
     */
    public function __construct()
    {
        $this->setTimestamp(null);
    }
}