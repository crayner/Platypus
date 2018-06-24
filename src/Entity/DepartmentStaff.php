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
 * Date: 24/06/2018
 * Time: 11:04
 */
namespace App\Entity;


class DepartmentStaff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @return array
     */
    public static function getRoleList(): array
    {
        return self::$roleList;
    }

    /**
     * Get id
     *
     * @return null|integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int|null $id
     * @return DepartmentStaff
     */
    public function setId(?int $id): DepartmentStaff
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $role;

    /**
     * @var array
     */
    private static $roleList = [
        'coordinator',
        'assistant_coordinator',
        'teacher_curriculum',
        'teacher',
        'director',
        'manager',
        'administrator',
        'other'
    ];

    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param null|string $role
     * @return DepartmentStaff
     */
    public function setRole(?string $role): DepartmentStaff
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @var Department|null
     */
    private $department;

    /**
     * @return Department|null
     */
    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    /**
     * setDepartment
     *
     * @param Department|null $department
     * @param bool $add
     * @return DepartmentStaff
     */
    public function setDepartment(?Department $department, $add = true): DepartmentStaff
    {
        if ($add && ! empty($department))
            $department->addMember($this, false);
        $this->department = $department;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $member;

    /**
     * @return Person|null
     */
    public function getMember(): ?Person
    {
        return $this->member;
    }

    /**
     * setMember
     *
     * @param Person|null $member
     * @param bool $add
     * @return DepartmentStaff
     */
    public function setMember(?Person $member, $add = true): DepartmentStaff
    {
        if ($add && ! empty($member))
            $member->addDepartment($this, false);
        $this->member = $member;
        return $this;
    }
}