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
 * Date: 21/09/2018
 * Time: 09:09
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CourseClass
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
     * @return CourseClass
     */
    public function setId(?int $id): CourseClass
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
    public function getName(bool $raw = false): ?string
    {
        if ($this->isUseCourseName() && ! $raw)
            return $this->getCourse()->getName().'.'.$this->name;
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return CourseClass
     */
    public function setName(?string $name): CourseClass
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
    public function getNameShort(bool $raw = false): ?string
    {
        if ($this->isUseCourseName() && ! $raw)
            return $this->getCourse()->getNameShort().'.'.$this->nameShort;
        return $this->nameShort;
    }

    /**
     * @param null|string $nameShort
     * @return CourseClass
     */
    public function setNameShort(?string $nameShort): CourseClass
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var boolean
     */
    private $reportable;

    /**
     * isReportable
     *
     * @return bool
     */
    public function isReportable(): bool
    {
        return $this->reportable = $this->reportable ? true : false ;
    }

    /**
     * setReportable
     *
     * @param bool|null $reportable
     * @return CourseClass
     */
    public function setReportable(?bool $reportable): CourseClass
    {
        $this->reportable = $reportable ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $attendance;

    /**
     * isAttendance
     *
     * @return bool
     */
    public function isAttendance(): bool
    {
        return $this->attendance = $this->attendance ? true : false ;
    }

    /**
     * setAttendance
     *
     * @param bool|null $attendance
     * @return CourseClass
     */
    public function setAttendance(?bool $attendance): CourseClass
    {
        $this->attendance = $attendance ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $useCourseName;

    /**
     * isUseCourseName
     *
     * @return bool
     */
    public function isUseCourseName(): bool
    {
        return $this->useCourseName = $this->useCourseName ? true : false ;
    }

    /**
     * setUseCourseName
     *
     * @param bool|null $useCourseName
     * @return CourseClass
     */
    public function setUseCourseName(?bool $useCourseName): CourseClass
    {
        $this->useCourseName = $useCourseName ? true : false ;
        return $this;
    }

    /**
     * @var Course|null
     */
    private $course;

    /**
     * @return Course|null
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @param Course|null $course
     * @return CourseClass
     */
    public function setCourse(?Course $course, $add = true): CourseClass
    {
        if ($add & ! empty($course))
            $course->addClass($this, false);
        $this->course = $course;
        return $this;
    }

    /**
     * @var Scale|null
     */
    private $useScale;

    /**
     * getUseScale
     *
     * @return Scale|null
     */
    public function getUseScale(): ?Scale
    {
        return $this->useScale;
    }

    /**
     * setUseScale
     *
     * @param Scale|null $useScale
     * @return CourseClass
     */
    public function setUseScale(?Scale $useScale): CourseClass
    {
        $this->useScale = $useScale;
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $people;

    /**
     * getPeople
     *
     * @return Collection
     */
    public function getPeople(): Collection
    {
        if (empty($this->people))
            $this->people = new ArrayCollection();

        $iterator = $this->people->getIterator();

        $iterator->uasort(
            function ($a, $b) {
                return ($a->getPerson()->getFullName() < $b->getPerson()->getFullName()) ? -1 : 1;
            }
        );

        $this->people = new ArrayCollection(iterator_to_array($iterator, false));

        return $this->people;
    }

    /**
     * @param Collection|null $people
     * @return CourseClass
     */
    public function setPeople(?Collection $people): CourseClass
    {
        $this->people = $people;
        return $this;
    }

    /**
     * addPerson
     *
     * @param CourseClassPerson|null $person
     * @param bool $add
     * @return CourseClass
     */
    public function addPerson(?CourseClassPerson $person, $add = true): CourseClass
    {
        if (empty($person) || $this->getPeople()->contains($person))
            return $this;

        if ($add)
            $person->setCourseClass($this, false);

        $this->people->add($person);

        return $this;
    }

    /**
     * removePerson
     *
     * @param CourseClassPerson|null $person
     * @return CourseClass
     */
    public function removePerson(?CourseClassPerson $person): CourseClass
    {
        $this->getPeople()->removeElement($person);

        if (! empty($person))
            $person->setCourseClass(null, false);

        return $this;
    }

    /**
     * @var Collection
     */
    private $students;

    /**
     * getStudents
     *
     * @return Collection
     */
    public function getStudents(): Collection
    {
        if (! empty($this->students))
            return $this->students;
        $this->students = new ArrayCollection();

        foreach($this->getPeople()->getIterator() as $person)
            if ($person->getRole() === 'student')
                $this->students->add($person);

        return $this->students;
    }

    /**
     * @var Collection
     */
    private $tutors;

    /**
     * getStudents
     *
     * @return Collection
     */
    public function getTutors(): Collection
    {
        if (! empty($this->tutors))
            return $this->tutors;
        $this->tutors = new ArrayCollection();

        foreach($this->getPeople()->getIterator() as $person)
            if (mb_strpos($person->getRole(), 'student') === false && mb_strpos($person->getRole(), '_left') === false)
                $this->tutors->add($person);

        return $this->tutors;
    }

    /**
     * @var Collection
     */
    private $former;

    /**
     * getStudents
     *
     * @return Collection
     */
    public function getFormer(): Collection
    {
        if (! empty($this->former))
            return $this->former;
        $this->former = new ArrayCollection();

        foreach($this->getPeople()->getIterator() as $person)
            if ( mb_strpos($person->getRole(), '_left') !== false)
                $this->former->add($person);

        return $this->former;
    }
}