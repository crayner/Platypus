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
 * Time: 12:48
 */
namespace App\Manager;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use App\Util\SchoolYearHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class CourseClassManager
 * @package App\Manager
 */
class CourseClassManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClass::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'details',
            'label' => 'Details',
            'include' => 'Course/class_details.html.twig',
            'message' => 'courseClassDetailsMessage',
            'translation' => 'Course',
        ],
        [
            'name' => 'participants',
            'label' => 'Participants',
            'include' => 'Course/class_participants.html.twig',
            'message' => 'courseClassParticipantsMessage',
            'translation' => 'Course',
        ],
    ];

    /**
     * removeClassParticipant
     *
     * @param int $id
     * @return CourseClassManager
     * @throws \Exception
     */
    public function removeClassParticipant(int $id): CourseClassManager
    {
        $person = $this->getRepository(Person::class)->find($id);

        if ($person instanceof Person && $this->getEntity() instanceof CourseClass)
        {
            $courseClassPerson = $this->getRepository(CourseClassPerson::class)->findOneBy(['person' => $person, 'courseClass' => $this->getEntity()]);

            if ($courseClassPerson instanceof CourseClassPerson) {
                $this->getEntityManager()->remove($courseClassPerson);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->addMessage('success', 'Removed %{person} from %{class}', ['%{person}' => $person->getFullName(), '%{class}' => $this->getEntity()->getName()], 'Course');
                return $this;
            }
            $this->getMessageManager()->addMessage('warning', 'I did not find a participant to remove from %{class}!', ['%{class}' => $this->getEntity()->getName()], 'Course');
            return $this;
        }
        $this->getMessageManager()->addMessage('danger', 'The class or person was not found and removal do not happen!', [], 'Course');
        return $this;
    }

    /**
     * @var array
     */
    private $studentCourseClassList;

    /**
     * @var array
     */
    private $preferredStudentCourseClassList;

    /**
     * getStudentCourseClassList
     *
     * @param Person $student
     * @return array
     */
    public function getStudentCourseClassList(Person $student): array
    {
        if (! empty($this->studentCourseClassList))
            return $this->studentCourseClassList->toArray();

        $enrolments = [];
        foreach($student->getCurrentEnrolments()->getIterator() as $w)
            $enrolments[] = $w->getYearGroup()->getId();

        $xxx = $this->getRepository()->createQueryBuilder('cc')
            ->select('cc, yg.id as ygId')
            ->orderBy('c.name')
            ->addOrderBy('cc.name')
            ->leftJoin('cc.course', 'c')
            ->where('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->leftJoin('c.yearGroups', 'yg')
            ->getQuery()
            ->getResult()
        ;

        $this->preferredStudentCourseClassList = new ArrayCollection();
        $this->studentCourseClassList = new ArrayCollection();

        foreach($xxx as $w) {
            if (in_array($w['ygId'], $enrolments))
                if (! $this->preferredStudentCourseClassList->contains($w[0]))
                    $this->preferredStudentCourseClassList->add($w[0]);
            if (! $this->studentCourseClassList->contains($w[0]))
                 $this->studentCourseClassList->add($w[0]);
        }

        return $this->studentCourseClassList->toArray();
    }

    /**
     * getPreferredStudentCourseClassList
     *
     * @return array
     */
    public function getPreferredStudentCourseClassList(): array
    {
        return $this->preferredStudentCourseClassList->toArray();
    }

    /**
     * getParticipants
     *
     * @return array
     */
    public function getParticipants(): Collection
    {
        $participants = new ArrayCollection(array_merge($this->getEntity()->getTutors()->toArray(),$this->getEntity()->getStudents()->toArray()));
        return $participants;
    }

    /**
     * getManagement
     *
     * @return Collection
     */
    public function getManagement(): Collection
    {
        $manage = [];
        if ($this->getAuthorizationChecker()->isGranted('USE_ROUTE', ['attendance_by_class']))
        {
            $action = [];
            $action['name'] = 'Attendance';
            $action['route'] = 'attendance_by_class';
            $action['route_params'] = ['entity' => $this->getEntity()->getId()];
            $action['icon'] = 'fas fa-users fa-fw';
            $manage[] = $action;
        }
        if ($this->getAuthorizationChecker()->isGranted('USE_ROUTE', ['attendance_by_class']))
        {
            $action = [];
            $action['name'] = 'Planner';
            $action['route'] = 'attendance_by_class';
            $action['route_params'] = ['entity' => $this->getEntity()->getId()];
            $action['icon'] = 'far fa-calendar-check fa-fw';
            $manage[] = $action;
        }
        if ($this->getAuthorizationChecker()->isGranted('USE_ROUTE', ['attendance_by_class']))
        {
            $action = [];
            $action['name'] = 'Markbook';
            $action['route'] = 'attendance_by_class';
            $action['route_params'] = ['entity' => $this->getEntity()->getId()];
            $action['icon'] = 'fas fa-book fa-flip-horizontal fa-fw';
            $manage[] = $action;
        }
        if ($this->getAuthorizationChecker()->isGranted('USE_ROUTE', ['attendance_by_class']))
        {
            $action = [];
            $action['name'] = 'Homework';
            $action['route'] = 'attendance_by_class';
            $action['route_params'] = ['entity' => $this->getEntity()->getId()];
            $action['icon'] = 'fas fa-home fa-fw';
            $manage[] = $action;
        }
        if ($this->getAuthorizationChecker()->isGranted('USE_ROUTE', ['attendance_by_class']))
        {
            $action = [];
            $action['name'] = 'Internal Assessment ';
            $action['route'] = 'attendance_by_class';
            $action['route_params'] = ['entity' => $this->getEntity()->getId()];
            $action['icon'] = 'fas fa-file-alt fa-fw';
            $manage[] = $action;
        }
        return new ArrayCollection($manage);
    }
}