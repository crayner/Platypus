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
 * Date: 14/11/2018
 * Time: 10:09
 */
namespace App\Entity;

use App\Util\PersonHelper;

/**
 * Class AttendanceLogCourseClass
 * @package App\Entity
 */
class AttendanceLogCourseClass
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
     * @return AttendanceLogCourseClass
     */
    public function setId(?int $id): AttendanceLogCourseClass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $classDate;

    /**
     * @return \DateTime|null
     */
    public function getClassDate(): ?\DateTime
    {
        return $this->classDate;
    }

    /**
     * @param \DateTime|null $classDate
     * @return AttendanceLogCourseClass
     */
    public function setClassDate(?\DateTime $classDate): AttendanceLogCourseClass
    {
        $this->classDate = $classDate;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $timestampTaken;

    /**
     * @return \DateTime|null
     */
    public function getTimestampTaken(): ?\DateTime
    {
        return $this->timestampTaken;
    }

    /**
     * @param \DateTime|null $timestampTaken
     * @return AttendanceLogCourseClass
     */
    public function setTimestampTaken(?\DateTime $timestampTaken): AttendanceLogCourseClass
    {
        $this->timestampTaken = $timestampTaken;
        return $this;
    }

    /**
     * @var CourseClass|null
     */
    private $courseClass;

    /**
     * @return CourseClass|null
     */
    public function getCourseClass(): ?CourseClass
    {
        return $this->courseClass;
    }

    /**
     * @param CourseClass|null $courseClass
     * @return AttendanceLogCourseClass
     */
    public function setCourseClass(?CourseClass $courseClass): AttendanceLogCourseClass
    {
        $this->courseClass = $courseClass;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $taker;

    /**
     * @return Person|null
     */
    public function getTaker(): ?Person
    {
        return $this->taker;
    }

    /**
     * @param Person|null $taker
     * @return AttendanceLogCourseClass
     */
    public function setTaker(?Person $taker): AttendanceLogCourseClass
    {
        $this->taker = $taker;
        return $this;
    }

    /**
     * prePersist
     *
     * @return AttendanceLogCourseClass
     */
    public function prePersist(): AttendanceLogCourseClass
    {
        $this->setTimestampTaken(new \DateTime('now'));
        $this->setTaker(PersonHelper::getCurrentPerson());
        return $this;
    }
}