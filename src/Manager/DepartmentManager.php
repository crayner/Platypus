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
 * Date: 23/06/2018
 * Time: 18:08
 */

namespace App\Manager;


use App\Entity\Department;
use App\Entity\DepartmentStaff;
use App\Manager\Traits\EntityTrait;
use App\Util\PersonNameHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

class DepartmentManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Department::class;

    /**
     * @var DepartmentStaffManager
     */
    private $departmentStaffManager;

    /**
     * DepartmentManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @param DepartmentStaffManager $departmentStaffManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, DepartmentStaffManager $departmentStaffManager)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->departmentStaffManager = $departmentStaffManager;
    }
    /**
     * getTabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return Yaml::parse("
department_details:
    label: department.details.tab
    include: School/department_details.html.twig
    message: departmentDetailsMessage
    translation: School
department_tutor_collection:
    label: department.staff.tab
    include: School/department_staff.html.twig
    message: departmentStaffMessage
    translation: School
    display: hasMembers
");
    }

    /**
     * hasMembers
     *
     * @return bool
     */
    public function hasMembers(): bool
    {
        if (empty($this->getDepartment()) || empty($this->getDepartment()->getId()))
            return false;

        return true;
    }

    /**
     * @var Department|null
     */
    private $department;

    /**
     * findDepartment
     *
     * @param $id
     * @return Department
     */
    public function findDepartment($id): Department
    {
        $this->department = $this->find(intval($id));
        return $this->getDepartment();
    }

    /**
     * getDepartment
     *
     * @return Department
     */
    public function getDepartment(): Department
    {
        if (empty($this->department))
            $this->department = new Department();

        return $this->department;
    }

    /**
     * @var DepartmentStaff|null
     */
    private $member;

    /**
     * removeMember
     *
     * @param $id
     * @param $cid
     */
    public function removeMember($id, $cid)
    {
        $this->findDepartment($id);

        if ($cid === 'ignore')
            return ;

        $this->getDepartment();

        $this->findMember($cid);

        if (empty($this->getMember())) {
            $this->getMessageManager()->add('warning', 'department.member.missing.warning', ['%{member}' => $cid], 'School');
            return;
        }

        if ($this->getDepartment()->getMembers()->contains($this->getMember())) {
            // Staff are NOT Deleted, but the DepartmentStaff link is deleted.
            $this->getDepartment()->removeMember($this->getMember());
            $this->getEntityManager()->remove($this->getMember());
            $this->getEntityManager()->persist($this->getDepartment());
            $this->getEntityManager()->flush();

            $this->getMessageManager()->add('success', 'department.member.removed.success', ['%{member}' => $this->getMember()->getMember()->getFullName()], 'School');
        } else {
            $this->getMessageManager()->add('info', 'department.member.removed.info', ['%{member}' => $this->getMember()->getMember()->getFullName()], 'School');
        }
        $this->getMessageManager()->add('warning', 'department.member.missing.warning', ['%{member}' => $cid], 'School');
        $this->getMessageManager()->add('info', 'department.member.removed.info', ['%{member}' => $this->getMember()->getMember()->getFullName()], 'School');
    }

    /**
     * findMember
     *
     * @param $id
     * @return DepartmentStaff|null
     */
    public function findMember($id): ?DepartmentStaff
    {
        $this->setMember($this->getDepartmentStaffManager()->find($id));

        return $this->getMember();
    }

    /**
     * getDepartmentStaffManager
     *
     * @return DepartmentStaffManager
     */
    public function getDepartmentStaffManager(): DepartmentStaffManager
    {
        return $this->departmentStaffManager;
    }

    /**
     * getMember
     *
     * @return DepartmentStaff|null
     */
    public function getMember(): ?DepartmentStaff
    {
        return $this->member;
    }

    /**
     * setMember
     *
     * @param DepartmentStaff|null $member
     * @return DepartmentManager
     */
    public function setMember(?DepartmentStaff $member): DepartmentManager
    {
        $this->member = $member;
        return $this;
    }

    /**
     * refreshDepartment
     *
     * @return Department|null
     */
    public function refreshDepartment(): ?Department
    {
        if (empty($this->department))
            return $this->department;

        try {
            $this->getEntityManager()->refresh($this->department);
            return $this->department->refresh();
        } catch (\Exception $e) {
            return $this->department;
        }
    }

    /**
     * getStaffList
     *
     * @param int|null $id
     * @return string
     */
    public function getStaffList(?int $id): string
    {
        $staff = $this->getEntityManager()->createQuery('SELECT p.surname, p.firstName, p.title, p.preferredName
                FROM ' . DepartmentStaff::class . ' d 
                LEFT JOIN d.member p 
                WHERE d.department = :department 
                ORDER BY p.surname, p.firstName')
            ->setParameter('department', $id)
            ->getArrayResult();
        $result = '';
        foreach($staff as $person)
            $result .= PersonNameHelper::getFullName($person, ['preferredOnly' => true])."<br/>\n";

        return $result ? trim($result, "<br/>\n"): 'None';
    }
}