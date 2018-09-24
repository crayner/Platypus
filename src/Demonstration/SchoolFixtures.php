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
 * Date: 20/05/2018
 * Time: 10:57
 */
namespace App\Demonstration;

use App\Entity\AlertLevel;
use App\Entity\AttendanceCode;
use App\Entity\DayOfWeek;
use App\Entity\Department;
use App\Entity\DepartmentStaff;
use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentField;
use App\Entity\Facility;
use App\Entity\FileExtension;
use App\Entity\House;
use App\Entity\INDescriptor;
use App\Entity\PersonRole;
use App\Entity\Scale;
use App\Entity\ScaleGrade;
use App\Entity\Setting;
use App\Entity\StudentNoteCategory;
use App\Entity\YearGroup;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class SchoolFixtures implements DummyDataInterface
{
    use buildTable;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @param LoggerInterface $logger
     */
    public function load(ObjectManager $manager, LoggerInterface $logger)
    {
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/day_of_week.yml'));
        $this->setLogger($logger)->setObjectManager($manager)->setMetaData(DayOfWeek::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/individual_needs_descriptor.yml'));
        $this->setMetaData(INDescriptor::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/student_note_category.yml'));
        $this->setMetaData(StudentNoteCategory::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/attendance_code.yml'));
        $this->setMetaData(AttendanceCode::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/file_extension.yml'));
        $this->setMetaData(FileExtension::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/facility.yml'));
        $this->setMetaData(Facility::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/house.yml'));
        $this->setMetaData(House::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/alert_level.yml'));
        $this->setMetaData(AlertLevel::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/year_group.yml'));
        $this->setMetaData(YearGroup::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/department.yml'));
        $this->setMetaData(Department::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/department_staff.yml'));
        $this->setMetaData(DepartmentStaff::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/external_assessment.yml'));
        $this->setMetaData(ExternalAssessment::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/scale.yml'));
        $this->setMetaData(Scale::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/external_assessment_field.yml'));
        $this->setMetaData(ExternalAssessmentField::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/scale_grade.yml'));
        $this->setMetaData(ScaleGrade::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/setting.yml'));
        $this->setMetaData(Setting::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/attendance_code.yml'));
        $this->setMetaData(Setting::class)->truncateTable()->buildTable($data);
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/attendance_code_person_role.yml'));
        $this->buildJoinTable($data ?: [], AttendanceCode::class, PersonRole::class,
            'attendance_code_id', 'person_role_id', 'addPersonRole');
    }
}