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
 * Date: 11/11/2018
 * Time: 11:58
 */
namespace App\Manager;

use App\Entity\AttendanceCode;
use App\Entity\AttendanceLogCourseClass;
use App\Entity\AttendanceLogPerson;
use App\Entity\CourseClass;
use App\Entity\Person;
use App\Util\PersonHelper;
use App\Util\SchoolYearHelper;
use App\Util\TimetableHelper;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AttendanceManager
 * @package App\Manager
 */
class AttendanceManager
{
    /**
     * AttendanceManager constructor.
     * @param TimetableHelper $helper
     */
    public function __construct(TimetableHelper $helper, CourseClassManager $courseClassManager, SettingManager $settingManager, AttendanceLogPersonManager $logPersonManager, AttendanceLogCourseClassManager $logCourseClassManager)
    {
        $this->courseClassManager = $courseClassManager;
        $this->logPersonManager = $logPersonManager;
        $this->logCourseClassManager = $logCourseClassManager;
        $this->setLongDateFormat($settingManager->get('date.format.long'))->setTimezone($settingManager->getParameter('timezone'));
    }

    /**
     * takeAttendanceByClass
     *
     * @param CourseClass|null $courseClass
     * @param string $date
     */
    public function takeAttendanceByClass(?CourseClass $courseClass, string $date): void
    {
        $this->setCourseClass($courseClass)->setDate($date);

        $longDate = $this->getDate()->format($this->getLongDateFormat());
        if (! $this->isValidDate())
        {
            $this->getCourseClassManager()->getMessageManager()->add('warning', 'The date "%{date}" is in the future, and is not acceptable', ['%{date}' => $longDate], 'Attendance');
            return ;
        }
        if (! $this->getCourseClass()->isCurrentSchoolYear())
        {
            $this->getCourseClassManager()->getMessageManager()->add('warning', 'The class "%{class}" is not in the current school year "%{year}"', ['%{class}' => $this->getCourseClass()->getName(), '%{year}' => SchoolYearHelper::getCurrentSchoolYear()->getName()], 'Attendance');
            return ;
        }
        if (! $this->isSchoolDay())
        {
            $this->getCourseClassManager()->getMessageManager()->add('warning', 'The date "%{date}" is not a school day.', ['%{date}' => $longDate], 'Attendance');
            return ;
        }
        if (! $this->isClassOnThisDay())
        {
            $this->getCourseClassManager()->getMessageManager()->add('warning', 'The class "%{class}" is not scheduled in the timetable for this day: "%{date}"', ['%{date}' => $longDate, '%{class}' => $this->getCourseClass()->getName()], 'Attendance');
            return ;
        }
        if (! $this->hasAttendanceBeenTaken())
        {
            $this->getCourseClassManager()->getMessageManager()->add('primary', 'Attendance has not been taken for %{class} yet for the %{date}. The entries below are a best-guess based on defaults and information put into the system in advance, not actual data.', ['%{date}' => $longDate, '%{class}' => $this->getCourseClass()->getName()], 'Attendance');
            return ;
        }
    }

    /**
     * isSchoolDay
     *
     * @return bool
     */
    public function isSchoolDay(): bool
    {
        return TimetableHelper::isSchoolDay($this->getDate());
    }

    /**
     * @var CourseClass
     */
    private $courseClass;

    /**
     * getCourseClass
     *
     * @return CourseClass
     */
    public function getCourseClass(): CourseClass
    {
        return $this->courseClass;
    }

    /**
     * setCourseClass
     *
     * @param CourseClass $courseClass
     * @return AttendanceManager
     */
    public function setCourseClass(CourseClass $courseClass): AttendanceManager
    {
        $this->courseClass = $courseClass;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * getDate
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        if (empty($this->date))
            $this->date = new \DateTime('now');
        return $this->date;
    }

    /**
     * setDate
     *
     * @param string|\DateTime $date
     * @return AttendanceManager
     */
    public function setDate($date): AttendanceManager
    {
        if (empty($date))
            $date = new \DateTime('now', new \DateTimeZone($this->timezone));
        if (! $date instanceof \DateTime)
            $date = new \DateTime($date, new \DateTimeZone($this->timezone));

        $this->date = new \DateTime($date->format('Y-m-d'), new \DateTimeZone($this->timezone));

        return $this;
    }

    /**
     * getMyClasses
     *
     * @return array
     */
    public function getMyClasses(): array
    {
        return $this->getCourseClassManager()->setEntity($this->getCourseClass())->getMyClasses();
    }

    /**
     * getAllClasses
     *
     * @return array
     */
    public function getAllClasses(): array
    {
        return $this->getCourseClassManager()->setEntity($this->getCourseClass())->getAllClasses();
    }

    /**
     * @var CourseClassManager
     */
    private $courseClassManager;

    /**
     * @return CourseClassManager
     */
    public function getCourseClassManager(): CourseClassManager
    {
        return $this->courseClassManager;
    }

    /**
     * isValidDate
     *
     * @param string $testDate
     * @return bool
     */
    private function isValidDate($testDate = '+1 Day'): bool
    {
        if ($this->getDate()->getTimestamp() >= strtotime($testDate))
            return false;
        return true;
    }

    /**
     * @var string
     */
    private $longDateFormat;

    /**
     * @return string
     */
    public function getLongDateFormat(): string
    {
        return $this->longDateFormat;
    }

    /**
     * @param string $longDateFormat
     * @return AttendanceManager
     */
    public function setLongDateFormat(string $longDateFormat): AttendanceManager
    {
        $this->longDateFormat = $longDateFormat;
        return $this;
    }

    /**
     * @var string
     */
    private $timezone = 'UTC';

    /**
     * getTimezone
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * setTimezone
     *
     * @param string $timezone
     * @return AttendanceManager
     */
    public function setTimezone(string $timezone): AttendanceManager
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * isClassOnThisDay
     *
     * @return bool
     */
    private function isClassOnThisDay(): bool
    {
        $result = $this->getCourseClassManager()->getEntityManager()->getRepository(CourseClass::class)->createQueryBuilder('cc')
            ->where('cc = :courseClass')
            ->select(['COUNT(tdrc.id) AS classes'])
            ->setParameter('courseClass', $this->getCourseClass())
            ->leftJoin('cc.timetableDayRowClasses', 'tdrc')
            ->leftJoin('tdrc.timetableDay', 'td')
            ->leftJoin('td.timetableDayDates', 'tdd')
            ->andWhere('tdd.date = :this_day')
            ->setParameter('this_day', $this->getDate())
            ->getQuery()
            ->getSingleScalarResult();

        if (empty($result))
            return false;
        return true;
    }

    /**
     * getClassStudents
     *
     * @return array
     */
    public function getClassStudents(): array
    {
        if (! empty($this->students))
            return $this->students;
        $this->students = [];
        foreach($this->getCourseClass()->getStudents() as $enrolment) {
            if ($enrolment->getPerson() && $enrolment->getPerson()->getStatus() === 'full') {
                $this->students[] = $this->addStudent($enrolment->getPerson());
            }
        }
        return $this->students;
    }

    /**
     * @var bool|null
     */
    private $attendanceBeenTaken = null;

    /**
     * hasAttendanceBeenTaken
     *
     * @return bool
     * @throws \Exception
     */
    private function hasAttendanceBeenTaken(): bool
    {
        if (is_null($this->attendanceBeenTaken))
            if (intval($this->getCourseClassManager()->getRepository(AttendanceLogCourseClass::class)->createQueryBuilder('alcc')
                ->select(['COUNT(alcc.id) AS stuff'])
                ->where('alcc.courseClass = :courseClass')
                ->andWhere('alcc.classDate = :classDate')
                ->setParameter('courseClass', $this->getCourseClass())
                ->setParameter('classDate', $this->getDate())
                ->getQuery()
                ->getSingleScalarResult()) === 1)
                    $this->attendanceBeenTaken = true;
            else
                $this->attendanceBeenTaken = false;

        return $this->attendanceBeenTaken;
    }

    /**
     * getStudentAbsentCount
     *
     * @param Person $student
     * @return int
     * @throws \Exception
     */
    public function getStudentAbsentCount(AttendanceLogPerson $student): int
    {
        return intval($this->getCourseClassManager()->getRepository(AttendanceLogPerson::class)->createQueryBuilder('alp')
            ->select(['COUNT(alp.id) AS stuff'])
            ->where('alp.courseClass = :courseClass')
            ->andWhere('alp.attendee = :student')
            ->leftJoin('alp.attendanceCode', 'ac')
            ->andWhere('ac.direction = :out')
            ->andWhere('ac.scope = :offsite')
            ->setParameter('courseClass', $this->getCourseClass())
            ->setParameter('student', $student->getAttendee())
            ->setParameter('out', 'out')
            ->setParameter('offsite', 'offsite')
            ->getQuery()
            ->getSingleScalarResult());
    }

    /**
     * getStudentAbsentCode
     *
     * @param Person $student
     * @return string
     * @throws \Exception
     */
    public function getStudentAbsentCode(AttendanceLogPerson $student): string
    {
        $attendanceCode = $student->getAttendanceCode() ? $student->getAttendanceCode()->getId() : 0 ;

        $element = '<div class="form-group">';
        $element .= '<select name="attendanceByClass['.$student->getAttendee()->getId().'][attendance_code]" id="attendance_by_class_'.$student->getId().'_attendance_code" class="form-control">';
        foreach($this->getAttendanceCodeList() as $q=>$w)
            $element .= '<option value="'.$q.'"'.($q === $attendanceCode ? ' selected': '').'>'.$w['name'].'</option>';
        $element .= '</select>';
        $element .= '</div>';
        return $element;
    }

    /**
     * @var array|null
     */
    private $attendanceCodeList;

    /**
     * getAttendanceCodeList
     *
     * @return array|null
     * @throws \Exception
     */
    public function getAttendanceCodeList(): ?array
    {
        if (empty($this->attendanceCodeList)){
            $this->attendanceCodeList = $this->getCourseClassManager()->getRepository(AttendanceCode::class)->createQueryBuilder('ac', 'ac.id')
                ->select(['ac.name', 'ac.id'])
                ->orderBy('ac.sequence', 'ASC')
                ->getQuery()
                ->getArrayResult();
        }
        return $this->attendanceCodeList;
    }

    /**
     * getStudentReason
     *
     * @param Person $student
     * @param array $reasons
     * @return string
     * @throws \Exception
     */
    public function getStudentReason(AttendanceLogPerson $student, array $reasons): string
    {
        $element = '<div class="form-group">';
        $element .= '<select name="attendanceByClass['.$student->getAttendee()->getId().'][reason]" id="attendance_by_class_'.$student->getId().'_reason" class="form-control">';
        $element .= '<option value="">'.$this->getCourseClassManager()->getTranslator()->trans('Select Reason ...', [], 'Attendance').'</option>';
        foreach($reasons as $q=>$w)
            $element .= '<option value="'.$w.'"'.($w === $student->getReason() ? ' selected': '').'>'.$w.'</option>';
        $element .= '</select>';
        $element .= '</div>';
        return $element;
    }

    /**
     * setTranslator
     *
     * @param TranslatorInterface $translator
     * @return AttendanceManager
     */
    public function setTranslator(TranslatorInterface $translator): AttendanceManager
    {
        $this->getCourseClassManager()->setTranslator($translator);
        return $this;
    }

    /**
     * @var array
     */
    private $students = [];

    /**
     * getStudentComment
     *
     * @param Person $student
     * @return string
     */
    public function getStudentComment(AttendanceLogPerson $student)
    {
        $element = '<div class="form-group">';
        $element .= '<input type="text" name="attendanceByClass['.$student->getAttendee()->getId().'][comment]" id="attendance_by_class_'.$student->getId().'_comment" class="form-control form-control-sm" value="'.$student->getComment().'">';
        $element .= '</div>';
        return $element;
    }

    /**
     * addStudent
     *
     * @param Person $student
     * @return AttendanceLogPerson
     * @throws \Exception
     */
    private function addStudent(Person $student)
    {
        if ($this->hasAttendanceBeenTaken()) {
            $attendanceLog = $this->getCourseClassManager()->getRepository(AttendanceLogPerson::class)->createQueryBuilder('alp')
                ->where('alp.courseClass = :courseClass')
                ->andWhere('alp.attendee = :student')
                ->andWhere('alp.classDate = :classDate')
                ->setParameter('courseClass', $this->getCourseClass())
                ->setParameter('student', $student)
                ->setParameter('classDate', $this->getDate())
                ->getQuery()
                ->getOneOrNullResult()?: new AttendanceLogPerson();
        } else
            $attendanceLog = new AttendanceLogPerson();

        $attendanceLog->setAttendee($student);
        $attendanceLog->setCourseClass($this->getCourseClass());
        $attendanceLog->setClassDate($this->getDate());
        $attendanceLog->setTaker(PersonHelper::getCurrentPerson());

        return $attendanceLog;
    }

    /**
     * saveAttendanceLogs
     *
     * @param $logs
     * @param ValidatorInterface $validator
     */
    public function saveAttendanceLogs($logs, ValidatorInterface $validator)
    {
        $logCC = $this->getLogCourseClassManager()->findOneBy(['classDate' => $this->getDate(), 'courseClass' => $this->getCourseClass()]) ?: new AttendanceLogCourseClass();
        $logCC->setCourseClass($this->getCourseClass());
        $logCC->setClassDate($this->getDate());
        $this->getLogCourseClassManager()->setEntity($logCC)->saveEntity($validator);
        if (in_array($this->getLogCourseClassManager()->getMessageManager()->getStatus(), ['warning', 'danger']))
            return ;

        foreach($logs as $log)
        {
            $attendee = $this->getCourseClassManager()->getRepository(Person::class)->find($log['attendee']);
            $logP = $this->getLogPersonManager()->findOneBy(['attendee' => $attendee, 'courseClass' => $this->getCourseClass(), 'classDate' => $this->getDate()]) ?: new AttendanceLogPerson();
            $logP->setCourseClass($this->getCourseClass());
            $logP->setClassDate($this->getDate());
            $logP->setAttendee($attendee);
            $logP->setAttendanceCode($this->getCourseClassManager()->getRepository(AttendanceCode::class)->find($log['attendance_code']));
            $logP->setReason($log['reason']);
            $logP->setComment($log['comment']);

            $this->getLogPersonManager()->setEntity($logP)->saveEntity($validator);
        }
    }

    /**
     * @var AttendanceLogCourseClassManager
     */
    private $logCourseClassManager;

    /**
     * @var AttendanceLogPersonManager
     */
    private $logPersonManager;

    /**
     * getLogCourseClassManager
     *
     * @return AttendanceLogCourseClassManager
     */
    public function getLogCourseClassManager(): AttendanceLogCourseClassManager
    {
        return $this->logCourseClassManager;
    }

    /**
     * getLogPersonManager
     *
     * @return AttendanceLogPersonManager
     */
    public function getLogPersonManager(): AttendanceLogPersonManager
    {
        return $this->logPersonManager;
    }
}