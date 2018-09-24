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

use App\Entity\Calendar;
use App\Entity\CalendarGrade;
use App\Entity\Course;
use App\Entity\SchoolYear;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use App\Entity\SpecialDay;
use App\Entity\Term;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class SchoolYearFixtures implements DummyDataInterface
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
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/school_year.yml'));
        $this->setLogger($logger)->setObjectManager($manager)->setMetaData(SchoolYear::class)->truncateTable()->buildTable($data);

/*        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/calendar_grade.yml'));

        $this->truncateTable()->buildTable($data, CalendarGrade::class, $manager);
*/
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/school_year_term.yml'));
        $this->setMetaData(SchoolYearTerm::class)->truncateTable()->buildTable($data);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/school_year_special_day.yml'));
        $this->setMetaData(SchoolYearSpecialDay::class)->truncateTable()->buildTable($data);

/*        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/course_calendar_grade.yml'));

        $this->buildJoinTable($data ?: [], Course::class, CalendarGrade::class,
            'course_id', 'calendar_grade_id', 'addCalendarGrade', $manager);
*/
    }
}