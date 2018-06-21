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

use App\Entity\AttendanceCode;
use App\Entity\Campus;
use App\Entity\Course;
use App\Entity\DayOfWeek;
use App\Entity\Department;
use App\Entity\INDescriptor;
use App\Entity\Invoice;
use App\Entity\Scale;
use App\Entity\Setting;
use App\Entity\StudentNoteCategory;
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
        $this->setLogger($logger);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/day_of_week.yml'));

        $this->buildTable($data, DayOfWeek::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/individual_needs_descriptor.yml'));

        $this->setLogger($logger)->buildTable($data, INDescriptor::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/student_note_category.yml'));

        $this->setLogger($logger)->buildTable($data, StudentNoteCategory::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/attendance_code.yml'));

        $this->setLogger($logger)->buildTable($data, AttendanceCode::class, $manager);

        /*        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/campus.yml'));

                $this->setLogger($logger)->buildTable($data, Campus::class, $manager);

                $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/space.yml'));

                $this->buildTable($data, Space::class, $manager);

                $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/department.yml'));

                $this->buildTable($data, Department::class, $manager);

                $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/course.yml'));

                $this->buildTable($data, Course::class, $manager);

                $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/scale.yml'));

                $this->buildTable($data, Scale::class, $manager);
        */
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/setting.yml'));

        $this->buildTable($data, Setting::class, $manager);
/*
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/translate.yml'));

        $this->buildTable($data, Translate::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/invoice.yml'));

        $this->buildTable($data ?: [], Invoice::class, $manager);
*/
    }
}