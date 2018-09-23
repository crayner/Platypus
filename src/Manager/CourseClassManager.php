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
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use Doctrine\DBAL\Connection;

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
     * getParticipants
     *
     * @return array
     */
    public function getParticipants(): array
    {
        foreach($this->getEntity()->getCourse()->getYearGroups() as $group)
            $yearGroups[] = $group->getId();
        $students = $this->getRepository(Person::class)->createQueryBuilder('p')
            ->select('p,rg.name')
            ->orderBy('rg.name', 'ASC')
            ->addOrderBy('p.surname', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->leftJoin('p.primaryRole', 'r')
            ->where('r.category = :role')
            ->andWhere('p.status = :status')
            ->setParameter('status', 'full')
            ->setParameter('role', 'student')
            ->leftJoin('p.enrolments', 'e')
            ->leftJoin('e.rollGroup', 'rg')
            ->leftJoin('e.yearGroup', 'y')
            ->andWhere('y.id in (:yearGroups)')
            ->setParameter('yearGroups', $yearGroups, Connection::PARAM_INT_ARRAY)
            ->getQuery()
            ->getResult();
        $r = [];

        foreach($students as $w)
            $r[] = $w;

        $results['students']['__Enrolable Students__'] = $r;

        $students = $this->getRepository(Person::class)->createQueryBuilder('p')
            ->orderBy('rg.name', 'ASC')
            ->addOrderBy('p.surname', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->leftJoin('p.primaryRole', 'r')
            ->where('r.category = :role')
            ->andWhere('p.status = :status')
            ->setParameter('status', 'full')
            ->setParameter('role', 'student')
            ->leftJoin('p.enrolments', 'e')
            ->leftJoin('e.rollGroup', 'rg')
            ->leftJoin('e.yearGroup', 'y')
            ->andWhere('y.id NOT IN (:yearGroups)')
            ->setParameter('yearGroups', $yearGroups, Connection::PARAM_INT_ARRAY)
            ->getQuery()
            ->getResult();
        $r = [];
        foreach($students as $w)
            $r[] = $w;
        $results['students']['__Other Students__'] = $r;

        $students = $this->getRepository(Person::class)->createQueryBuilder('p')
            ->orderBy('p.surname', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->leftJoin('p.primaryRole', 'r')
            ->where('r.category != :role')
            ->andWhere('p.status = :status')
            ->setParameter('status', 'full')
            ->setParameter('role', 'student')
            ->getQuery()
            ->getResult();
        $r = [];
        foreach($students as $w)
            $r[] = $w;
        $results['tutors'] = $r;

        $students = $this->getRepository(Person::class)->createQueryBuilder('p')
            ->select("p.id, CONCAT(p.surname,':',p.firstName) AS fullName")
            ->orderBy('p.surname', 'ASC')
            ->addOrderBy('p.firstName', 'ASC')
            ->andWhere('p.status != :status')
            ->setParameter('status', 'full')
            ->getQuery()
            ->getResult();
        $r = [];
        foreach($students as $w)
            $r[] = $w;
        $results['former'] = $r;


        dump($results);
        return $results;
    }
}