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
 * Time: 16:52
 */
namespace App\Entity;

/**
 * Class NotificationListener
 * @package App\Entity
 */
class NotificationListener
{
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
     * @return NotificationListener
     */
    public function setId(?int $id): NotificationListener
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var null|string
     */
    private $scopeType;

    /**
     * @return null|string
     */
    public function getScopeType(): ?string
    {
        return $this->scopeType;
    }

    /**
     * @param null|string $scopeType
     * @return NotificationListener
     */
    public function setScopeType(?string $scopeType): NotificationListener
    {
        $this->scopeType = $scopeType;
        return $this;
    }

    /**
     * @var null|integer
     */
    private $scopeID;

    /**
     * @return int|null
     */
    public function getScopeID(): ?int
    {
        return $this->scopeID;
    }

    /**
     * @param int|null $scopeID
     * @return NotificationListener
     */
    public function setScopeID(?int $scopeID): NotificationListener
    {
        $this->scopeID = $scopeID;
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
     * @return NotificationListener
     */
    public function setPerson(?Person $person): NotificationListener
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var null|NotificationEvent
     */
    private $notificationEvent;

    /**
     * @return NotificationEvent|null
     */
    public function getNotificationEvent(): ?NotificationEvent
    {
        return $this->notificationEvent;
    }

    /**
     * setNotificationEvent
     *
     * @param NotificationEvent|null $notificationEvent
     * @param bool $add
     * @return NotificationListener
     */
    public function setNotificationEvent(?NotificationEvent $notificationEvent, bool $add = true): NotificationListener
    {
        if (!empty($notificationEvent) && $add)
            $notificationEvent->addNotificationListener($this, false);

        $this->notificationEvent = $notificationEvent;
        return $this;
    }
}