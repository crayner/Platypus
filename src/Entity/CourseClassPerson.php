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
 * Time: 11:54
 */

namespace App\Entity;


class CourseClassPerson
{
    /**
     * @var integer|null
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return CourseClassPerson
     */
    public function setId(?int $id): CourseClassPerson
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
        'student','teacher','assistant','technician','parent','student_left','teacher_left'
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
     * @return CourseClassPerson
     */
    public function setRole(?string $role): CourseClassPerson
    {
        $this->role = $role;
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
     * @return CourseClassPerson
     */
    public function setReportable(?bool $reportable): CourseClassPerson
    {
        $this->reportable = $reportable ? true : false ;
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
     * setCourseClass
     *
     * @param CourseClass|null $courseClass
     * @param bool $add
     * @return CourseClassPerson
     */
    public function setCourseClass(?CourseClass $courseClass, $add = true): CourseClassPerson
    {
        if (empty($courseClass) && ! empty($this->courseClass) && $add)
            $this->courseClass->removePerson($this);

        $this->courseClass = $courseClass;
        if ($add && ! empty($courseClass))
            $courseClass->addPerson($this, false);

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
     * @return CourseClassPerson
     */
    public function setPerson(?Person $person): CourseClassPerson
    {
        $this->person = $person;
        return $this;
    }
}