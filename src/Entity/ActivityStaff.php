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

class ActivityStaff
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
     * @return ActivityStaff
     */
    public function setId(?int $id): ActivityStaff
    {
        $this->id = $id;
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
     * @return ActivityStaff
     */
    public function setActivity(?Activity $activity): ActivityStaff
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * @var array
     */
    public static $roleList = [
        'organiser',
        'coach',
        'assistant',
        'other',
    ];

    /**
     * @var string|null
     */
    private $role;

    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return in_array($this->role, self::$roleList) ? $this->role : 'organiser';
    }

    /**
     * @param null|string $role
     * @return ActivityStaff
     */
    public function setRole(?string $role): ActivityStaff
    {
        $this->role = in_array($role, self::$roleList) ? $role : 'organiser';
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
     * @return ActivityStaff
     */
    public function setPerson(?Person $person): ActivityStaff
    {
        $this->person = $person;
        return $this;
    }
}