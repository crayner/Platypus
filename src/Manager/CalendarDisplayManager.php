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
 * Date: 4/11/2018
 * Time: 16:46
 */
namespace App\Manager;

use App\Entity\DayOfWeek;
use App\Entity\SchoolYear;
use App\Organism\Day;
use App\Util\SchoolYearHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CalendarDisplayManager
 * @package App\Manager
 */
class CalendarDisplayManager
{
    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * CalendarDisplayManager constructor.
     * @param SettingManager $settingManager
     * @throws \Exception
     */
    public function __construct(SettingManager $settingManager, TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->settingManager = $settingManager;
        $this->setSchoolYear(SchoolYearHelper::getCurrentSchoolYear());
        $this->getDaysOfWeek();
        $this->createYear();
    }

    /**
     * @var SchoolYear
     */
    private $schoolYear;

    /**
     * getSchoolYear
     *
     * @return SchoolYear
     */
    public function getSchoolYear(): SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * setSchoolYear
     *
     * @param SchoolYear $schoolYear
     * @return CalendarDisplayManager
     */
    public function setSchoolYear(SchoolYear $schoolYear): CalendarDisplayManager
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * @var Collection
     */
    private $daysOfWeek;

    /**
     * @return Collection
     */
    public function getDaysOfWeek(): Collection
    {
        if (empty($this->daysOfWeek))
            $this->daysOfWeek = new ArrayCollection($this->getEntityManager()->getRepository(DayOfWeek::class)->findBy([],['sequence' => 'ASC']));
        return $this->daysOfWeek;
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->getSettingManager()->getEntityManager();
    }

    /**
     * @var Collection
     */
    private $days;

    /**
     * @return Collection
     */
    public function getDays(): Collection
    {

        if (empty($this->days))
            $this->days = new ArrayCollection();

        return $this->days;
    }

    /**
     * @param Collection $days
     * @return CalendarDisplayManager
     */
    public function setDays(Collection $days): CalendarDisplayManager
    {
        $this->days = $days;
        return $this;
    }

    /**
     * addDay
     *
     * @param Day $day
     * @return CalendarDisplayManager
     */
    public function addDay(Day $day): CalendarDisplayManager
    {
        if ($this->getDays()->contains($day))
            return $this;

        $this->days->set($day->getDate()->format('Ymd'), $day);

        return $this;
    }

    /**
     * getDay
     *
     * @param string|\DateTime $date
     * @return Day|null
     */
    public function getDay($date): ?Day
    {
        if ($date instanceof \DateTime)
            $date = $date->format('Y-m-d');

        $date = new \DateTime($date);

        if (empty($date))
            trigger_error(sprintf('The date supply for a day is empty.'), E_USER_ERROR);


        $day = $this->getDays()->get($date->format('Ymd'));
        return $day;
    }

    /**
     * createYear
     *
     * @return CalendarDisplayManager
     * @throws \Exception
     */
    private function createYear(): CalendarDisplayManager
    {
        $start = clone $this->getSchoolYear()->getFirstDay();
        if ($start->add(new \DateInterval('P1Y'))->sub(new \DateInterval('P1D'))->format('Y-m-d') !== $this->getSchoolYear()->getLastDay()->format('Y-m-d'))
            trigger_error(sprintf('The School Year MUST be a full calendar year, not "%s" to "%s"',$this->getSchoolYear()->getFirstDay()->format('Y-m-d'),$this->getSchoolYear()->getLastDay()->format('Y-m-d')), E_USER_ERROR);

        $start = clone $this->getSchoolYear()->getFirstDay();
        $week = 1;
        do {
            $day = new Day($start, $this->getSettingManager(), $week);
            $day->setTermBreak($this->isTermBreak($start));
            $day->setClosed($day->isTermBreak(), $this->addTranslation('Term Break'));
            if ($this->isSpecialDay($day)) {
                $day->setClosed($this->isClosed($day), $this->getClosedPrompt($day));
                $day->setSpecial(true, $this->getSpecialDayPrompt($day));
            }
            $this->addDay($day);
            $start->add(new \DateInterval('P1D'));
            if (intval($start->format('N')) === $day->getFirstDayofWeek())
                $week++;
        } while ($start <= $this->getSchoolYear()->getLastDay());

        return $this;
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
     * isTermBreak
     *
     * @param \DateTime $date
     * @return bool
     */
    private function isTermBreak(\DateTime $date): bool
    {
        foreach($this->getSchoolYear()->getTerms() as $term)
            if ($date->format('Y-m-d') >= $term->getFirstDay()->format('Y-m-d') && $date->format('Y-m-d') <= $term->getLastDay()->format('Y-m-d') )
                return false;
        return true;
    }

    /**
     * @return TranslatorInterface
     */
    private function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }
    /**
     * addTranslation
     *
     * @param null|string $id
     * @param array $parameters
     * @param string $domain
     * @return string
     */
    private function addTranslation(?string $id, string $domain = 'Timetable', array $parameters = []): string
    {
        if (empty($id))
            return '';
        return $this->getTranslator()->trans($id, $parameters, $domain);
    }

    /**
     * isSpecialDay
     *
     * @param \DateTime $date
     * @return bool
     */
    private function isSpecialDay(Day $day): bool
    {
        $date = $day->getDate();
        if ($this->getSchoolYear()->getSpecialDays()->containsKey($date->format('Ymd')))
            return true;
        return false;
    }

    /**
     * getSpecialDayPrompt
     *
     * @param \DateTime $date
     * @return string
     */
    private function getSpecialDayPrompt(Day $day): string
    {
        $date = $day->getDate();
        $day = $this->getSchoolYear()->getSpecialDays()->get($date->format('Ymd'));
        if ($day)
            return $day->getName() ?: '';
        return '';

    }

    /**
     * isClosed
     *
     * @param Day $day
     * @return bool
     */
    private function isClosed(Day $day): bool
    {
        $date = $day->getDate();
        $day = $this->getSchoolYear()->getSpecialDays()->get($date->format('Ymd'));
        if ($day->getType() === 'school_closure')
            return true;
        return false;
    }

    /**
     * getClosedPrompt
     *
     * @param Day $day
     * @return string
     */
    private function getClosedPrompt(Day $day): string
    {
        if (!$this->isClosed($day))
            return '';
        $date = $day->getDate();
        $day = $this->getSchoolYear()->getSpecialDays()->get($date->format('Ymd'));
        return $day->getName() ?: '';
    }
}