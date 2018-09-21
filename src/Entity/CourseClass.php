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
    public function getName(): ?string
    {
        if ($this->useCourseName)
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
    public function getNameShort(): ?string
    {
        if ($this->useCourseName)
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
}