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
 * Date: 20/09/2018
 * Time: 20:54
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

class Course
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
     * @return Course
     */
    public function setId(?int $id): Course
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
     * @return Course
     */
    public function setName(?string $name): Course
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
     * @return Course
     */
    public function setNameShort(?string $nameShort): Course
    {
        $this->nameShort = $nameShort;
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
     * @return Course
     */
    public function setDescription(?string $description): Course
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var boolean
     */
    private $map;

    /**
     * @return bool
     */
    public function isMap(): bool
    {
        return $this->map = $this->map ? true : false ;
    }

    /**
     * @param bool|null $map
     * @return Course
     */
    public function setMap(?bool $map): Course
    {
        $this->map = $map ? true : false ;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequence;

    /**
     * @return int|null
     */
    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    /**
     * @param int|null $sequence
     * @return Course
     */
    public function setSequence(?int $sequence): Course
    {
        $this->sequence = $sequence ?: 0;
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
     * @return Course
     */
    public function setSchoolYear(?SchoolYear $schoolYear): Course
    {
        $this->schoolYear = $schoolYear;
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
     * @param Department|null $department
     * @return Course
     */
    public function setDepartment(?Department $department): Course
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @var Collection
     */
    private $yearGroups;

    /**
     * @return Collection
     */
    public function getYearGroups(): Collection
    {
        if (empty($this->yearGroups))
            $this->yearGroups = new ArrayCollection();

        if ($this->yearGroups instanceof PersistentCollection)
            $this->yearGroups->initialize();

        return $this->yearGroups;
    }

    /**
     * @param Collection|null $yearGroups
     * @return Course
     */
    public function setYearGroups(?Collection $yearGroups): Course
    {
        $this->yearGroups = $yearGroups;
        return $this;
    }

    /**
     * addYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return Course
     */
    public function addYearGroup(?YearGroup $yearGroup): Course
    {
        if (empty($course) || $this->getYearGroups()->contains($yearGroup))
            return $this;

        $this->yearGroups->add($yearGroup);

        return $this;
    }

    /**
     * removeYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return Course
     */
    public function removeYearGroup(?YearGroup $yearGroup): Course
    {
        $this->getYearGroups()->removeElement($yearGroup);
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $classes;

    /**
     * @return Collection|null
     */
    public function getClasses(): ?Collection
    {
        if (empty($this->classes))
            $this->classes = new ArrayCollection();

        if ($this->classes instanceof PersistentCollection)
            $this->classes-> initialize();

        return $this->classes;
    }

    /**
     * @param Collection|null $classes
     * @return Course
     */
    public function setClasses(?Collection $classes): Course
    {
        $this->classes = $classes;
        return $this;
    }

    /**
     * addClass
     *
     * @param CourseClass|null $class
     * @param bool $add
     * @return Course
     */
    public function addClass(?CourseClass $class, $add = true): Course
    {
        if (empty($class) || $this->getClasses()->contains($class))
            return $this;

        if ($add)
            $class->setCourse($this, false);

        $this->classes->add($class);

        return $this;
    }

    /**
     * removeClass
     *
     * @param CourseClass|null $class
     * @return Course
     */
    public function removeClass(?CourseClass $class): Course
    {
        $this->getClasses()->removeElement($class);
        if (! empty($class))
            $class->setCourse(null, false);
        return $this;
    }
}