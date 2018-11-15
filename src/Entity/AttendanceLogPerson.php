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
 * Time: 10:27
 */
namespace App\Entity;

use App\Util\PersonHelper;

/**
 * Class AttendanceLogPerson
 * @package App\Entity
 */
class AttendanceLogPerson
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * getContextTypeList
     *
     * @return array
     */
    public static function getContextTypeList(): array
    {
        return self::$contextTypeList;
    }

    /**
     * getDirectionTypeList
     *
     * @return array
     */
    public static function getDirectionTypeList(): array
    {
        return self::$directionTypeList;
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
     * @return AttendanceLogPerson
     */
    public function setId(?int $id): AttendanceLogPerson
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
     * @return AttendanceLogPerson
     */
    public function setClassDate(?\DateTime $classDate): AttendanceLogPerson
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
     * @return AttendanceLogPerson
     */
    public function setTimestampTaken(?\DateTime $timestampTaken): AttendanceLogPerson
    {
        $this->timestampTaken = $timestampTaken;
        return $this;
    }

    /**
     * @var string|null
     */
    private $direction;

    /**
     * @var array
     */
    private static $directionTypeList = [
        'in',
        'out'
    ];

    /**
     * @return null|string
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param null|string $direction
     * @return AttendanceLogPerson
     */
    public function setDirection(?string $direction): AttendanceLogPerson
    {
        $this->direction = in_array($direction, self::getDirectionTypeList()) ? $direction : null;
        return $this;
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
     * @return AttendanceLogPerson
     */
    public function setType(?string $type): AttendanceLogPerson
    {
        $this->type = $type ?: null;
        return $this;
    }

    /**
     * @var null|string
     */
    private $reason;

    /**
     * @return null|string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param null|string $reason
     * @return AttendanceLogPerson
     */
    public function setReason(?string $reason): AttendanceLogPerson
    {
        $this->reason = $reason ?: null;
        return $this;
    }

    /**
     * @var null|string
     */
    private $context;

    /**
     * @var array
     */
    private static $contextTypeList = [
            'roll_group',
            'class',
            'person',
            'future',
            'self_registration'
        ];

    /**
     * @return null|string
     */
    public function getContext(): ?string
    {
        return $this->context ?: null ;
    }

    /**
     * @param null|string $context
     * @return AttendanceLogPerson
     */
    public function setContext(?string $context): AttendanceLogPerson
    {
        $this->context = in_array($context, self::getContextTypeList()) ? $context : null ;
        return $this;
    }

    /**
     * @var null|string
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return AttendanceLogPerson
     */
    public function setComment(?string $comment): AttendanceLogPerson
    {
        $this->comment = $comment;
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
     * @return AttendanceLogPerson
     */
    public function setCourseClass(?CourseClass $courseClass): AttendanceLogPerson
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
     * @return AttendanceLogPerson
     */
    public function setTaker(?Person $taker): AttendanceLogPerson
    {
        $this->taker = $taker;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $attendee;

    /**
     * @return Person|null
     */
    public function getAttendee(): ?Person
    {
        return $this->attendee;
    }

    /**
     * @param Person|null $attendee
     * @return AttendanceLogPerson
     */
    public function setAttendee(?Person $attendee): AttendanceLogPerson
    {
        $this->attendee = $attendee;
        return $this;
    }

    /**
     * @var AttendanceCode|null
     */
    private $attendanceCode;

    /**
     * @return AttendanceCode|null
     */
    public function getAttendanceCode(): ?AttendanceCode
    {
        return $this->attendanceCode;
    }

    /**
     * @param AttendanceCode|null $attendanceCode
     * @return AttendanceLogPerson
     */
    public function setAttendanceCode(?AttendanceCode $attendanceCode): AttendanceLogPerson
    {
        $this->attendanceCode = $attendanceCode;
        return $this;
    }

    /**
     * prePersist
     *
     * @return AttendanceLogPerson
     */
    public function prePersist(): AttendanceLogPerson
    {
        $this->setTimestampTaken(new \DateTime('now'));
        $this->setTaker(PersonHelper::getCurrentPerson());
        return $this;
    }
}