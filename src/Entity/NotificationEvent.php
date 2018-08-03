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
 * Time: 16:29
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class NotificationEvent
 * @package App\Entity
 */
class NotificationEvent
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
     * @return NotificationEvent
     */
    public function setId(?int $id): NotificationEvent
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var null|string
     */
    private $event;

    /**
     * @return null|string
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @param null|string $event
     * @return NotificationEvent
     */
    public function setEvent(?string $event): NotificationEvent
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @var array
     */
    private static $typeList = [
        'core',
        'additional',
        'cli',
    ];

    /**
     * geyTypeList
     *
     * @return array
     */
    public static function geyTypeList()
    {
        return self::$typeList;
    }

    /**
     * @var null|string
     */
    private $type;

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return NotificationType
     */
    public function setType(?string $type): NotificationType
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @var null|array
     */
    private $scopes;

    /**
     * @return array|null
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    /**
     * @param array|null $scopes
     * @return NotificationEvent
     */
    public function setScopes(?array $scopes): NotificationEvent
    {
        $this->scopes = $scopes;
        return $this;
    }

    /**
     * @var null|boolean
     */
    private $active;

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool|null $active
     * @return NotificationEvent
     */
    public function setActive(?bool $active): NotificationEvent
    {
        $this->active = $active;
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
     * @return NotificationEvent
     */
    public function setModule(?Module $module): NotificationEvent
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @var null|Action
     */
    private $action;

    /**
     * @return Action|null
     */
    public function getAction(): ?Action
    {
        return $this->action;
    }

    /**
     * @param Action|null $action
     * @return NotificationEvent
     */
    public function setAction(?Action $action): NotificationEvent
    {
        $this->action = $action;
        return $this;
    }

    /**
     * NotificationEvent constructor.
     */
    public function __construct()
    {
        $this->type = 'core';
        $this->scopes = ['All'];
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getModule()->getName().'/'.$this->getEvent();
    }

    /**
     * @var null|Collection
     */
    private $notificationListeners;

    /**
     * @return Collection|null
     */
    public function getNotificationListeners(): ?Collection
    {
        if (empty($this->notificationListeners))
            $this->notificationListeners = new ArrayCollection();

        if ($this->notificationListeners instanceof PersistentCollection)
            $this->notificationListeners->initialize();

        return $this->notificationListeners;
    }

    /**
     * @param Collection|null $notificationListeners
     * @return NotificationEvent
     */
    public function setNotificationListeners(?Collection $notificationListeners): NotificationEvent
    {
        $this->notificationListeners = $notificationListeners;
        return $this;
    }

    /**
     * addNotificationListener
     *
     * @param NotificationListener|null $listener
     * @param bool $add
     * @return NotificationEvent
     */
    public function addNotificationListener(?NotificationListener $listener, bool $add = true): NotificationEvent
    {
        if (empty($listener) || $this->getNotificationListeners()->contains($listener))
            return $this;

        if ($add)
            $listener->setNotificationEvent($this, false);

        $this->notificationListeners->add($listener);

        return $this;
    }

    /**
     * removeNotificationListener
     *
     * @param NotificationListener|null $listener
     * @return NotificationEvent
     */
    public function removeNotificationListener(?NotificationListener $listener): NotificationEvent
    {
        $this->getNotificationListeners()->removeElement($listener);

        return $this;
    }
}