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
 * Date: 2/11/2018
 * Time: 15:13
 */
namespace App\Manager;

use App\Entity\CourseClass;
use App\Entity\DayOfWeek;
use App\Entity\SchoolYear;
use App\Entity\TimetableColumnRow;
use App\Entity\TimetableDay;
use App\Entity\TimetableDayDate;
use App\Util\PersonHelper;
use App\Util\PersonNameHelper;
use App\Util\SchoolYearHelper;
use App\Util\UserHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class TimetableDisplayManager
 * @package App\Manager
 */
class TimetableDisplayManager
{
    /**
     * @var TimetableManager
     */
    private $manager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @var CalendarDisplayManager
     */
    private $calendarManager;

    /**
     * @var string
     */
    private $timeZone;

    /**
     * @var array
     */
    private $timetables = [];

    /**
     * TimetableDisplayManager constructor.
     * @param TimetableManager $manager
     * @param TranslatorInterface $translator
     * @param SettingManager $settingManager
     * @param CalendarDisplayManager $calendarManager
     */
    public function __construct(TimetableManager $manager, TranslatorInterface $translator, SettingManager $settingManager, CalendarDisplayManager $calendarManager)
    {
        $this->manager = $manager;
        $this->translator = $translator;
        $this->settingManager = $settingManager;
        $this->timeZone = $settingManager->get('system.timezone', 'UTC');
        $this->calendarManager = $calendarManager;
    }

    /**
     * @var array
     */
    private $data;

    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        if (empty($this->data))
            $this->buildTimetable();
        return $this->data;
    }

    /**
     * @param array $data
     * @return TimetableDisplayManager
     */
    public function setData(array $data): TimetableDisplayManager
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @var array
     */
    private $messages;

    /**
     * getMessages
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages ?: [];
    }

    /**
     * buildTimetable
     *
     */
    private function buildTimetable()
    {
        $this->data = [];
        $this->data['control']['date'] = $this->setDateData('now', 'now');
        $this->data['default'] = $this->defineDefault();
        $this->controlDefaults();
        $this->translateMessages();
    }

    /**
     * addTranslation
     *
     * @param null|string $id
     * @param array $parameters
     * @param string $domain
     * @return string
     */
    public function addTranslation(?string $id, string $domain = 'Timetable', array $parameters = []): string
    {
        if (empty($id))
            return '';
        return $this->translator->trans($id, $parameters, $domain);
    }

    /**
     * defineDefault
     *
     * @return array
     */
    private function defineDefault(): array
    {
        if ($this->getSetting('system.index_text', null) !== null) {

            $say = $this->getSetting('system.index_text');
            $say = explode("\n", $say);
            $content = [];
            foreach($say as $words)
                $content[] = [
                    'content' => str_replace(["\n", '<p>','</p>'], '', $words),
                    'type' => 'p',
                ];
        } else {
            $content = [
                [
                    'content' => $this->addTranslation('home.para1', 'System'),
                    'type' => 'p',
                ],
                [
                    'content' => $this->addTranslation('home.para2', 'System'),
                    'type' => 'p',
                ],
                [
                    'content' => $this->addTranslation('home.para3', 'System'),
                    'type' => 'p',
                ],
                [
                    'content' => 'Craig Rayner',
                    'type' => 'p',
                ],
            ];
        }

        return $content;
    }

    /**
     * getSetting
     *
     * @param string $name
     * @param null $default
     * @param array $options
     * @return null
     */
    private function getSetting(string $name, $default = null, array $options = [])
    {
        return $this->settingManager->get($name, $default, $options);
    }

    /**
     * getParameter
     *
     * @param string $name
     * @return mixed|null
     */
    private function getLocale()
    {
        return $this->settingManager->getLocale();
    }

    /**
     * handleData
     *
     * @param $data
     */
    public function handleData($data){
        if (empty($data))
        {
            $this->data = null;
            return;
        }
        $type = $data['data-type'];
        $date = $data['data-date'];
        unset($data['data-type'],$data['data-date']);
        $this->setData($data);
        $this->data['control']['date'] = $this->setDateData($date, $type);
        $this->controlDefaults();
    }

    /**
     * setDateData
     *
     * @param string $dataDate
     * @param string $action
     * @return array
     * @throws \Exception
     */
    private function setDateData(string $dataDate, string $action): array
    {
        $date = [];
        $date['minDate'] = SchoolYearHelper::getCurrentSchoolYear()->getFirstDay()->format('Y-m-d');
        $date['maxDate'] = SchoolYearHelper::getCurrentSchoolYear()->getLastDay()->format('Y-m-d');
        $date['value'] = new \DateTime($dataDate, $this->getTimeZone());
        $date['format']['long'] = $this->getSetting('date.format.long');
        $date['format']['short'] = $this->getSetting('date.format.short');
        switch ($action) {
            case 'prevWeek':
                $date['value']->sub(new \DateInterval('P7D'));
                break;
            case 'nextWeek':
                $date['value']->add(new \DateInterval('P7D'));
                break;
            case 'now':
                $date['value'] = new \DateTime('now', $this->getTimeZone());
                break;
        }

        if ($date['value']->format('Y-m-d') > $date['maxDate'])
            $date['value'] = new \DateTime($date['maxDate'], $this->getTimeZone());

        if ($date['value']->format('Y-m-d') < $date['minDate'])
            $date['value'] = new \DateTime($date['minDate'], $this->getTimeZone());

        $date['value'] = $date['value']->format('Y-m-d');
        $today = date('Y-m-d');
        $this->data['control']['home']['disabled'] = false;
        if ($today > $date['maxDate'] || $today < $date['minDate'])
            $this->data['control']['home']['disabled'] = true;

        return $date;
    }

    /**
     * translateMessages
     *
     */
    private function translateMessages()
    {
        $this->data['translate']['prevWeek'] = $this->addTranslation('Move to previous group');
        $this->data['translate']['nextWeek'] = $this->addTranslation('Move to next group.');
        $this->data['translate']['today'] = $this->addTranslation('Return to today.');
        $this->data['translate']['week_number'] = $this->addTranslation('week_number', 'Timetable', ['%{number}' => $this->data['control']['week']]);
        $this->data['translate']['time'] = $this->addTranslation('time');
        $this->data['translate']['phone'] = $this->addTranslation('phone');
    }

    /**
     * controlDefaults
     *
     */
    private function controlDefaults()
    {
        $this->data['control']['render'] = false;
        $this->data['control']['locale'] = $this->getLocale();
        $this->data['control']['style'] = 'multiDay';
        $this->determineTimetableType();
        $this->getDisplayDays();
    }

    /**
     * determineTimetableType
     *
     */
    private function determineTimetableType()
    {
        $this->currentUserTimetable();
    }


    /**
     * currentUserTimetable
     *
     */
    private function currentUserTimetable()
    {
        $user = UserHelper::getCurrentUser();
        if (! empty($user)) {
            PersonHelper::setUser($user);
            $this->data['person'] = [];
            $this->data['person']['name'] = PersonNameHelper::getFullName(PersonHelper::getPerson());
            $this->data['person']['id'] = PersonHelper::getPerson()->getId();
            $this->data['header']['title'] = $this->addTranslation('My Timetable');
            $this->data['control']['render'] = true;
            $this->data['control']['individual'] = true;

            $timetable = $this->mapUserPeriods($user, SchoolYearHelper::getCurrentSchoolYear());

 //           $this->data['control']['timetable'] = $timetable->getId(); //@todo serialise timetable...
        }
    }
    /**
     * getTimeZone
     *
     * @return \DateTimeZone
     */
    public function getTimeZone(): \DateTimeZone
    {
        return new \DateTimeZone($this->timeZone ?: 'UTC');
    }

    /**
     * getDisplayDays
     *
     * @return array
     * @throws \Exception
     */
    private function getDisplayDays(): array
    {
        $days = [];
        $date = new \DateTime($this->data['control']['date']['value'], $this->getTimeZone());
        $day = $this->getCalendarManager()->getDay($date);
        while(intval($date->format('N')) !== $day->getFirstDayofWeek()) {
            $date->sub(new \DateInterval('P1D'));
        }

        $end = clone $date;
        $end->add(new \DateInterval('P10D'));

        $tdays = $this->getEntityManager()->getRepository(TimetableDayDate::class)->createQueryBuilder('tdd')
            ->leftJoin('tdd.timetableDay', 'td')
            ->leftJoin('td.timetable', 't')
            ->where('t.id IN (:timetables)')
            ->setParameter('timetables', $this->timetables, Connection::PARAM_INT_ARRAY)
            ->setParameter('startDate', $date)
            ->setParameter('endDate', $end)
            ->andWhere('tdd.date >= :startDate')
            ->andWhere('tdd.date <= :endDate')
            ->getQuery()
            ->getResult()
        ;

        $this->data['control']['week'] = null;
        while (count($days) < 5) {
            $day = $this->getCalendarManager()->getDay($date);
            if (is_null($this->data['control']['week']))
                $this->data['control']['week'] = $day->getWeekNumber();
            if ($day->isSchoolDay()) {
                foreach ($tdays as $w) {
                    if ($day->getDate()->format('Y-m-d') === $w->getDate()->format('Y-m-d')) {
                        $day->setTimetableDay($w->getTimetableDay());
                        break;
                    }
                }
                $days[] = $day->__toObject();
            }
            $date->add(new \DateInterval('P1D'));
        }
        foreach($days as $q=>$w)
        {
            $this->data['control']['columns'][$q]['day'] = $w;
        }

        return $days;
    }

    /**
     * getCalendarManager
     *
     * @return CalendarDisplayManager
     */
    public function getCalendarManager(): CalendarDisplayManager
    {
        return $this->calendarManager;
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    private function getEntityManager(): EntityManagerInterface
    {
        return $this->getSettingManager()->getEntityManager();
    }

    /**
     * getSettingManager
     *
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
     * mapUserPeriods
     *
     * @param UserInterface $user
     * @return array
     */
    public function mapUserPeriods(UserInterface $user, SchoolYear $schoolYear): array
    {
        $result = [];

        PersonHelper::setUser($user);
        $person = PersonHelper::getPerson();

        $classes = $this->getEntityManager()->getRepository(CourseClass::class)->createQueryBuilder('cc')
            ->leftJoin('cc.people', 'ccp')
            ->where('ccp.person = :person')
            ->setParameter('person', $person)
            ->leftJoin('ccp.person', 'p')
            ->leftJoin('cc.course', 'c')
            ->andWhere('c.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear)
            ->leftJoin('cc.timetableDayRowClasses', 'cl')
            ->leftJoin('cl.timetableDay', 'td')
            ->leftJoin('td.timetable', 't')
            ->leftJoin('cl.timetableColumnRow', 'period')
            ->leftJoin('cl.facility', 'f')
            ->select(["CONCAT(c.nameShort,'.',cc.nameShort) AS className", "cc.id AS classId", "td.nameShort AS dayName", "td.id as dayId", "period.name AS columnRowName", "period.id AS columnRowId", "t.id AS timetableId","p.id AS personId", "f.id AS facilityId", "f.name AS facilityName", "f.phoneInt"])
            ->getQuery()
            ->getResult()
        ;

        $timetables = [];

        foreach($classes as $w)
            $timetables[$w['timetableId']] =$w['timetableId'];

        $periods = $this->getEntityManager()->getRepository(TimetableColumnRow::class)->createQueryBuilder('period')
            ->leftJoin('period.timetableColumn', 'tc')
            ->leftJoin('tc.timetableDays', 'td')
            ->leftJoin('td.timetable', 't')
            ->where('t.id IN (:timetables)')
            ->setParameter('timetables', $timetables, Connection::PARAM_STR_ARRAY)
            ->orderBy('tc.id', 'ASC')
            ->addOrderBy('period.timeStart', 'ASC')
            ->getQuery()
            ->getResult();

        $x = [];
        $y = null;
        $z = null;
        foreach($periods as $period) {
            if ($period->getTimetableColumn()->getId() !== $z) {
                if (is_null($y))
                    $y = 0;
                else
                    $y++;
                $z = $period->getTimetableColumn()->getId();
            }
            $p = json_decode($period->serialise(), true);
            foreach($classes as $w)
            {
                if ($period->getId() === $w['columnRowId'])
                {
                    $p['class'] = $w;
                    break;
                }
            }
            if (empty($p['class']))
                $p['class'] = false;
            $x[$y]['periods'][] = $p;
        }

        $this->data['control']['columns'] = $x;

        $this->data['control']['startTime'] = '23:59:59';
        $this->data['control']['endTime'] = '00:00:00';
        foreach($this->getEntityManager()->getRepository(DayOfWeek::class)->findBy([],['sequence' => 'ASC']) as $day)
        {
            if ($day->isSchoolDay()){
                if ($this->data['control']['startTime'] > $day->getSchoolStart()->format('H:i:s'))
                    $this->data['control']['startTime'] = $day->getSchoolStart()->format('H:i:s');
                if ($this->data['control']['endTime'] < $day->getSchoolEnd()->format('H:i:s'))
                    $this->data['control']['endTime'] = $day->getSchoolEnd()->format('H:i:s');
            }
        }
        $time = new \DateTime($this->data['control']['startTime']);
        $this->data['control']['startTime']  = $time->sub(new \DateInterval('PT15M'))->format('H:i:s');

        $time = new \DateTime($this->data['control']['endTime']);
        $this->data['control']['endTime']  = $time->add(new \DateInterval('PT15M'))->format('H:i:s');

        $this->data['control']['showTimes'] = true;
        $this->timetables = $timetables;

        return $result;
    }

    /**
     * getTimetables
     *
     * @return array
     */
    public function getTimetables(): array
    {
        return $this->timetables;
    }
}