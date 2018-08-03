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
 * Date: 2/08/2018
 * Time: 15:39
 */
namespace App\Entity;

/**
 * Class Notification
 * @package App\Entity
 */
class Notification
{
    /**
     * @var \DateTime
     */
    private $timeStamp;

    /**
     * @return \DateTime
     */
    public function getTimeStamp(): \DateTime
    {
        return $this->timeStamp;
    }

    /**
     * @param \DateTime $timeStamp
     * @return Notification
     */
    public function setTimeStamp(): Notification
    {
        $this->timeStamp = new \DateTime('now');
        return $this;
    }

    /**
     * @var null|integer
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
     * @return Notification
     */
    public function setId(?int $id): Notification
    {
        $this->id = $id;
        return $this;
    }

    /**
     * getStatusList
     *
     * @return array
     */
    public function getStatusList()
    {
        return self::$statusList;
    }

    /**
     * @var array
     */
    private static $statusList = [
        'new',
        'archived',
    ];

    /**
     * @var null|string
     */
    private $status;

    /**
     * getStatus
     *
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * setStatus
     *
     * @param null|string $status
     * @return Notification
     */
    public function setStatus(?string $status): Notification
    {
        $this->status = $status === 'new' ? 'new' : 'Archived';
        return $this;
    }

    /**
     * Notification constructor.
     */
    public function __construct()
    {
        $this->status = 'new';
        $this->count = 1;
    }

    /**
     * @var null|integer
     */
    private $count;

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     * @return Notification
     */
    public function setCount(?int $count): Notification
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @var null|string
     */
    private $message;

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param null|string $message
     * @return Notification
     */
    public function setMessage(?string $message): Notification
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @var null|string
     */
    private $actionLink;

    /**
     * @return null|string
     */
    public function getActionLink(): ?string
    {
        return $this->actionLink;
    }

    /**
     * @param null|string $actionLink
     * @return Notification
     */
    public function setActionLink(?string $actionLink): Notification
    {
        $this->actionLink = $actionLink;
        return $this;
    }

    /**
     * @var null|Person
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
     * @return Notification
     */
    public function setPerson(?Person $person): Notification
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var null|Module
     */
    private $module;

    /**
     * @return Module|null
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * @param Module|null $module
     * @return Notification
     */
    public function setModule(?Module $module): Notification
    {
        $this->module = $module;
        return $this;
    }
}