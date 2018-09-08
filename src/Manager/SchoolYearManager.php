<?php
namespace App\Manager;

use App\Entity\SchoolYear;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use App\Manager\Traits\EntityTrait;
use App\Repository\SchoolYearRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class SchoolYearManager
 * @package App\Manager
 */
class SchoolYearManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = SchoolYear::class;

	/**
	 * @var EntityManagerInterface
	 */
	private $manager;

	/**
	 * @var Form
	 */
	private $form;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var Year
	 */
	private $year;

    /**
     * @var MessageManager
     */
	private $messageManager;

    /**
     * @var string
     */
    private $status;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var SettingManager
     */
    private $settingManager;

	/**
	 * YearManager constructor.
	 *
	 * @param ObjectManager $manager
	 */
	public function __construct(Year $year,
                                RouterInterface $router, RequestStack $stack,
                                SettingManager $settingManager)
	{
		$this->manager = $settingManager->getEntityManager();
		$this->year = $year;
        $this->messageManager = $settingManager->getMessageManager();
        $this->messageManager->setDomain('SchoolYear');
        $this->router = $router;
        $this->stack = $stack;
        $this->settingManager = $settingManager;
	}

    /**
	 * @return EntityManagerInterface
	 */
	public function getEntityManager(): EntityManagerInterface
	{
		return $this->manager;
	}

	/**
	 * @param FormEvent $event
	 */
	public function preSubmit(FormEvent $event)
	{
		$this->data = $event->getData();
		$this->form = $event->getForm();

		$year = $this->form->getData();

		if (isset($this->data['terms']) && is_array($this->data['terms']))
		{
			foreach ($this->data['terms'] as $q => $w)
			{
				$w['year']               = $year->getId();
				$this->data['terms'][$q] = $w;
			}
		}

		if (isset($this->data['specialDays']) && is_array($this->data['specialDays']))
		{
			foreach ($this->data['specialDays'] as $q => $w)
			{
				$w['year']                     = $year->getId();
				$this->data['specialDays'][$q] = $w;
			}
		}

		$event->setData($this->data);

		return $event;

	}

    /**
     * @return SchoolYear
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->getEntity();
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return SchoolYearManager
     */
    public function setStatus(string $status): SchoolYearManager
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return SchoolYearTerm|null
     */
    public function getTerm(): ?SchoolYearTerm
    {
        return $this->term;
    }

    /**
     * getSpecialDay
     *
     * @return SchoolYearSpecialDay|null
     */
    public function getSpecialDay(): ?SchoolYearSpecialDay
    {
        return $this->specialDay;
    }

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
	 * @param $sql
	 * @param $rsm
	 */
	private function executeQuery($sql, $rsm)
	{

		$query = $this->manager->createNativeQuery($sql, $rsm);
		try
		{
			$query->execute();
		}
		catch (DriverException $e)
		{
			if (!in_array($e->getErrorCode(), ['1091']))
				throw $e;
		}
        catch (PDOException $e)
        {
            if (!in_array($e->getErrorCode(), ['1146']))
                throw $e;
        }
	}

	/**
	 * Can Delete
	 *
	 * @param SchoolYear|array $year
	 *
	 * @return bool
	 */
	public function canDelete($schoolYear): bool
	{
	    if ($schoolYear instanceof SchoolYear && ! $schoolYear->canDelete())
		    return false;

        if ($schoolYear instanceof SchoolYear)
            $schoolYear = (array) $schoolYear;

	    if ($schoolYear['status'] === 'current')
	        return false;

	    if ($this->getEntityManager()->createQuery('SELECT COUNT(t.id) FROM ' . SchoolYearTerm::class . ' t WHERE t.schoolYear = :schoolYear')
            ->setParameter('schoolYear', $schoolYear['id'])
            ->getSingleScalarResult() > 0)
	        return false;

	    return true;
	}

	/**
	 * @return SchoolYearRepository
	 */
	public function getSchoolYearRepository(): SchoolYearRepository
	{
		return $this->getRepository();
	}

    /**
     * @var array
     */
	protected $tabs = [
        [
            'name' => 'schoolYear',
            'label' => 'school_year.details.tab',
            'include' => 'SchoolYear/school_year_tab.html.twig',
            'message' => 'schoolYearMessage',
            'translation' => 'SchoolYear',
        ],
        [
            'name' => 'terms',
            'label' => 'school_year.terms.tab',
            'include' => 'SchoolYear/terms.html.twig',
            'message' => 'termMessage',
            'translation' => 'SchoolYear',
        ],
        [
            'name' => 'specialDays',
            'label' => 'school_year.specialDays.tab',
            'include' => 'SchoolYear/special_days.html.twig',
            'message' => 'specialDayMessage',
            'translation' => 'SchoolYear',
        ],
    ];

	/**
	 * @param SchoolYear $schoolYear
	 *
	 * @return Year
	 */
	public function generate(SchoolYear $schoolYear)
	{
		$this->schoolYear = $schoolYear;
		return $this->year->generate($this->getSchoolYear());
	}

	/**
	 * Set SchoolYear Day Types
	 * 
	 * @param Year     $year
	 * @param SchoolYear $schoolYear
	 *
	 * @return SchoolYear
	 */
	public function setSchoolYearDays(Year $year, SchoolYear $schoolYear)
	{
		$this->year     = $year;
		$this->schoolYear = $schoolYear;
		$this->setNonSchoolDays();
		$this->setTermBreaks();
		$this->setClosedDays();
		$this->setSpecialDays();

		return $this->getSchoolYear();
	}

	/**
	 * Set Non School Day
	 */
	public function setNonSchoolDays()
	{
		$schoolDays = $this->year->getSettingManager()->get('schoolweek');

		foreach ($this->year->getMonths() as $monthKey => $month)
		{
			foreach ($month->getWeeks() as $weekKey => $week)
			{
				foreach ($week->getDays() as $dayKey => $day)
				{
					// School Day ?
					if (!in_array($day->getDate()->format('D'), $schoolDays))
						$day->setSchoolDay(false);
					else
						$day->setSchoolDay(true);
				}
				$month->getWeeks()[$weekKey] = $week;
			}
			$this->year->getMonths()[$monthKey] = $month;
		}
	}

	/**
	 * Set Term Breaks
	 */
	public function setTermBreaks()
	{
		foreach ($this->year->getMonths() as $monthKey => $month)
		{
			foreach ($month->getWeeks() as $weekKey => $week)
			{
				foreach ($week->getDays() as $dayKey => $day)
				{
					// School Day ?
					$break = $this->isTermBreak($day);
					$this->year->getDay($day->getDate()->format('d.m.Y'))->setTermBreak($break);
					$day->setTermBreak($break);
					$week->getDays()[$dayKey] = $day;
				}
				$month->getWeeks()[$weekKey] = $week;
			}
			$this->year->getMonths()[$monthKey] = $month;
		}
	}

	/**
	 * @param Day $currentDate
	 *
	 * @return bool
	 */
	public function isTermBreak(Day $currentDate)
	{
		// Check if the day is a possible school day. i.e. Ignore Weekends
		if ($currentDate->isTermBreak()) return true;

		foreach ($this->getSchoolYear()->getTerms() as $term)
		{
			if ($currentDate->getDate() >= $term->getFirstDay() && $currentDate->getDate() <= $term->getLastDay())
				return false;
		}

		$currentDate->setTermBreak(true);

		return true;
	}

	/**
	 *
	 */
	public function setClosedDays()
	{
		if (!is_null($this->getSchoolYear()->getSpecialDays()))
			foreach ($this->getSchoolYear()->getSpecialDays() as $specialDay)
				if ($specialDay->getType() == 'closure')
					$this->year->getDay($specialDay->getDay()->format('d.m.Y'))->setClosed(true, $specialDay->getName());
	}

	/**
	 *
	 */
	public function setSpecialDays()
	{
		if (!is_null($this->getSchoolYear()->getSpecialDays()))
			foreach ($this->getSchoolYear()->getSpecialDays() as $specialDay)
				if ($specialDay->getType() != 'closure')
					$this->year->getDay($specialDay->getDay()->format('d.m.Y'))->setSpecial(true, $specialDay->getName());
	}

	/**
	 * @param Day  $day
	 * @param null $class
	 *
	 * @return string
	 */
	public function getDayClass(Day $day, $class = null)
	{

		$class    = empty($class) ? '' : $class;
		$weekDays = $this->year->getSettingManager()->get('schoolWeek');
		$weekEnd  = true;

		if (isset($weekDays[$day->getDate()->format('D')]))
			$weekEnd = false;
		if (!$weekEnd)
			$class .= ' dayBold';

		if ($this->isTermBreak($day))
			$class .= ' termBreak';
		if ($day->isClosed())
		{
			$class .= ' isClosed';
			$class = str_replace(' termBreak', '', $class);
		}
		if ($day->isSpecial())
		{
			$class .= ' isSpecial';
			$class = str_replace(' termBreak', '', $class);
		}

		if (!$day->isSchoolDay())
		{
			$class .= ' isNonSchoolDay';
			$class = str_replace(' termBreak', '', $class);
		}

		if (empty($class)) return '';

		return ' class="' . trim($class) . '"';
	}

	/**
	 * @return array
	 */
	public function getSchoolYearList()
	{
		$results = $this->getEntityManager()->getRepository(SchoolYear::class)->findBy([], ['firstDay' => 'DESC']);
		return empty($results) ? [] : $results ;
	}

    /**
     * @param SchoolYear $schoolYear
     * @return bool
     */
    public function isCurrentSchoolYear(SchoolYear $schoolYear)
    {
        $current = $this->getCurrentSchoolYear();

        if ($current->getId() === $schoolYear->getId() && $current->getName() === $schoolYear->getName())
            return true;
        return false;
    }

    /**
     * @return array
     */
    public function getCurrentYears(): array
    {
        $x = [];
        $x[] = $this->getCurrentSchoolYear()->getFirstDay()->format('Y');
        $x[] = $this->getCurrentSchoolYear()->getLastDay()->format('Y');
        return $x;
    }

    /**
     * @param $cid
     */
    public function removeTerm($cid)
    {
        $this->setStatus('default');
        if ($cid === 'ignore')
            return ;

        if (! $this->getSchoolYear())
            return ;

        $this->findTerm($cid);

        $this->setStatus('warning');

        if (empty($this->term)) {
            $this->messageManager->add('warning', 'term.missing.warning', ['%{term}' => $cid]);
            return ;
        }

        if ($this->getSchoolYear()->getTerms()->contains($this->term) && $this->term->canDelete()) {
            // Staff is NOT Deleted, but the DepartmentMember link is deleted.
            $this->getSchoolYear()->removeTerm($this->term);
            $this->getEntityManager()->remove($this->term);
            $this->getEntityManager()->persist($this->getSchoolYear());
            $this->getEntityManager()->flush();

            $this->setStatus('success');
            $this->messageManager->add('success', 'term.removed.success', ['%{term}' => $this->term->getFullName()]);
        } else {
            $this->setStatus('info');
            $this->messageManager->add('info', 'term.removed.info', ['%{term}' => $this->term->getFullName()]);
        }
    }

    /**
     * @var null|SchoolYearTerm
     */
    private $term;

    /**
     * @param $id
     * @return null|SchoolYearTerm
     */
    public function findTerm($id): ?SchoolYearTerm
    {
        $this->term = $this->getEntityManager()->getRepository(SchoolYearTerm::class)->find(intval($id));

        return $this->getTerm();
    }

    /**
     * @param $cid
     */
    public function removeSpecialDay($cid)
    {
        $this->setStatus('default');
        if ($cid === 'ignore')
            return ;

        if (! $this->getSchoolYear())
            return ;

        $this->findSpecialDay($cid);

        $this->setStatus('warning');

        if (empty($this->specialDay)) {
            $this->messageManager->add('warning', 'special_day.missing.warning', ['%{specialDay}' => $cid]);
            return ;
        }

        if ($this->getSchoolYear()->getSpecialDays()->contains($this->specialDay) && $this->specialDay->canDelete()) {
            $this->getSchoolYear()->removeSpecialDay($this->specialDay);
            $this->getEntityManager()->remove($this->specialDay);
            $this->getEntityManager()->persist($this->getSchoolYear());
            $this->getEntityManager()->flush();

            $this->setStatus('success');
            $this->messageManager->add('success', 'special_day.removed.success', ['%{specialDay}' => $this->specialDay->getFullName($this->getSettingManager()->get('date.format.long'))]);
        } else {
            $this->setStatus('info');
            $this->messageManager->add('info', 'special_day.removed.info', ['%{specialDay}' => $this->specialDay->getFullName($this->getSettingManager()->get('date.format.long'))]);
        }
    }

    /**
     * @var null|SchoolYearSpecialDay
     */
    private $specialDay;

    /**
     * findSpecialDay
     *
     * @param $id
     * @return SchoolYearSpecialDay|null
     */
    public function findSpecialDay($id): ?SchoolYearSpecialDay
    {
        $this->specialDay = $this->getEntityManager()->getRepository(SchoolYearSpecialDay::class)->find(intval($id));

        return $this->getSpecialDay();
    }

    /**
     * @return string
     */
    public function getResetScripts(): string
    {
        $request = $this->stack->getCurrentRequest();
        $xx = "manageCollection('" . $this->router->generate("term_manage", ["id" => $request->get("id"), "cid" => "ignore"]) . "','termCollection', '')\n";
        $xx .= "manageCollection('" . $this->router->generate("special_day_manage", ["id" => $request->get("id"), "cid" => "ignore"]) . "','specialDayCollection', '')\n";

        return $xx;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isDisplay($method = []): bool
    {
        return true;
    }

    /**
     * getGradeInCurrentSchoolYear
     *
     * @param Student $student
     * @return SchoolYearGrade|null
     */
    public static function getStudentGradeInCurrentSchoolYear(Student $student): ?SchoolYearGrade
    {
        $grades = $student->getSchoolYearGrades();

        foreach ($grades as $grade)
            if ($grade->getSchoolYear() === self::getCurrentSchoolYear())
                return $grade->getSchoolYearGrade();

        return null;
    }

    /**
     * findLast
     *
     * @return SchoolYear
     * @throws \Exception
     */
    public function findLast(SchoolYear $schoolYear): SchoolYear
    {
        $lastYear = $this->getRepository()->createQueryBuilder('y')
            ->orderBy('y.lastDay', 'DESC')
            ->setMaxResults( 1 )
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ($lastYear === null)
            return $schoolYear;

        $interval = new \DateInterval('P1Y');
        $schoolYear->setFirstDay(date_add($lastYear->getFirstDay(), $interval));
        $schoolYear->setLastDay(date_add($lastYear->getLastDay(), $interval));
        $schoolYear->setSequence($lastYear->getSequence() + 1);
        if ($schoolYear->getFirstDay()->format('Y') !== $schoolYear->getLastDay()->format('Y'))
            $schoolYear->setName($schoolYear->getFirstDay()->format('Y') . '-' . $schoolYear->getLastDay()->format('Y'));
        else
            $schoolYear->setName($schoolYear->getFirstDay()->format('Y'));

        return $schoolYear;
    }
}