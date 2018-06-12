<?php
namespace App\Manager;

use App\Entity\SchoolYearGrade;
use App\Entity\SchoolYear;
use App\Entity\SpecialDay;
use App\Entity\Student;
use App\Entity\Term;
use App\Repository\SchoolYearRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Yaml\Yaml;

class SchoolYearManager implements TabManagerInterface
{
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
	 * @var TokenStorageInterface
	 */
	private static $tokenStorage;

	/**
	 * @var UserInterface
	 */
	private static $currentUser;

    /**
     * @var SchoolYear
     */
    private static $currentSchoolYear;

    /**
     * @var SchoolYear
     */
    private static $nextSchoolYear;

	/**
	 * @var SchoolYearRepository
	 */
	private static $schoolYearRepository;

	/**
	 * @var Year
	 */
	private $year;

	/**
	 * @var  SchoolYear
	 */
	private $schoolYear;

    /**
     * @var MessageManager
     */
	private $messageManager;

    /**
     * @var string
     */
    private $status;

    /**
     * @var SchoolYearGradeManager
     */
    private $schoolYearGradeManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $stack;

	/**
	 * YearManager constructor.
	 *
	 * @param ObjectManager $manager
	 */
	public function __construct(EntityManagerInterface $manager, TokenStorageInterface $tokenStorage, Year $year, MessageManager $messageManager,
                                RouterInterface $router, RequestStack $stack)
	{
		$this->manager = $manager;
		self::$tokenStorage = $tokenStorage;
		self::$schoolYearRepository = $manager->getRepository(SchoolYear::class);
		$this->year = $year;
        $this->messageManager = $messageManager;
        $this->messageManager->setDomain('SchoolYear');
//       $this->calendarGradeManager = $schoolYearGradeManager;
  //      $this->calendarGradeManager->setSchoolYearManager($this);
        $this->router = $router;
        $this->stack = $stack;
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
        return $this->calendar;
    }

    /**
     * @return SchoolYearGrade|null
     */
    public function getSchoolYearGrade(): ?SchoolYearGrade
    {
        return $this->calendarGrade;
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
     * @return Term|null
     */
    public function getTerm(): ?Term
    {
        return $this->term;
    }

    /**
     * @return Term|null
     */
    public function getSpecialDay(): ?SpecialDay
    {
        return $this->specialDay;
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
				throw new \Exception($e->getMessage());
		}
        catch (PDOException $e)
        {
            if (!in_array($e->getErrorCode(), ['1146']))
                throw new \Exception($e->getMessage());
        }
	}

	/**
	 * Can Delete
	 *
	 * @param SchoolYear $year
	 *
	 * @return bool
	 */
	public function canDelete(SchoolYear $schoolYear): bool
	{
		return $schoolYear->canDelete();
	}

    /**
     * getCurrentUser
     *
     */
    private static function getCurrentUser() 
	{
		if (! is_null(self::$currentUser))
			return ;
		$token = self::$tokenStorage->getToken();

		if (is_null($token))
			return ;

		$user = $token->getUser();
		if ($user instanceof UserInterface)
			self::$currentUser = $user;

		return;
	}

    /**
     * getCurrentSchoolYear
     *
     * @return SchoolYear|null
     */
    public static function getCurrentSchoolYear(): ?SchoolYear
	{
		if (! is_null(self::$currentSchoolYear))
			return self::$currentSchoolYear;

		self::getCurrentUser();
		if (self::$currentUser instanceof UserInterface)
		{
			$settings = self::$currentUser->getUserSettings();
			if (isset($settings['calendar']))
				self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['id' => $settings['calendar']]);
			else
				self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['status' => 'current']);
		}
		else
			self::$currentSchoolYear = self::$schoolYearRepository->findOneBy(['status' => 'current']);

		return self::$currentSchoolYear;
	}

	/**
	 * @return SchoolYearRepository
	 */
	public static function getSchoolYearRepository(): SchoolYearRepository
	{
		return self::$schoolYearRepository;
	}

	/**
	 * @return array
	 */
	public function getTabs(): array
	{
		return Yaml::parse("
calendar:
    label: calendar.calendar.tab
    include: SchoolYear/calendarTab.html.twig
    message: calendarMessage
    translation: SchoolYear
terms:
    label: calendar.terms.tab
    include: SchoolYear/terms.html.twig
    message: termMessage
    translation: SchoolYear
specialDays:
    label: calendar.specialDays.tab
    include: SchoolYear/specialDays.html.twig
    message: specialDayMessage
    translation: SchoolYear
calendarGrades:
    label: calendar.calendar_grades.tab
    include: SchoolYear/calendar_grades.html.twig
    message: calendarGradeMessage
    translation: SchoolYear
");
	}

	/**
	 * @param SchoolYear $schoolYear
	 *
	 * @return Year
	 */
	public function generate(SchoolYear $schoolYear)
	{
		$this->calendar = $schoolYear;
		return $this->year->generate($this->calendar);
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
		$this->calendar = $schoolYear;
		$this->setNonSchoolDays();
		$this->setTermBreaks();
		$this->setClosedDays();
		$this->setSpecialDays();

		return $this->calendar;
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

		foreach ($this->calendar->getTerms() as $term)
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
		if (!is_null($this->calendar->getSpecialDays()))
			foreach ($this->calendar->getSpecialDays() as $specialDay)
				if ($specialDay->getType() == 'closure')
					$this->year->getDay($specialDay->getDay()->format('d.m.Y'))->setClosed(true, $specialDay->getName());
	}

	/**
	 *
	 */
	public function setSpecialDays()
	{
		if (!is_null($this->calendar->getSpecialDays()))
			foreach ($this->calendar->getSpecialDays() as $specialDay)
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
     * @param SchoolYear|null $schoolYear
     * @return null|SchoolYear
     */
    public static function getNextSchoolYear(?SchoolYear $schoolYear): ?SchoolYear
    {
        if (self::$nextSchoolYear && is_null($schoolYear))
            return self::$nextSchoolYear;

        $schoolYear = $schoolYear ?: self::getCurrentSchoolYear();

        $result = self::getSchoolYearRepository()->createQueryBuilder('c')
            ->where('c.firstDay > :firstDay')
            ->setParameter('firstDay', $schoolYear->getFirstDay()->format('Y-m-d'))
            ->getQuery()
            ->getResult();

        self::$nextSchoolYear = $result ? $result[0] : null ;

        return self::$nextSchoolYear;
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
     * @param $id
     * @return SchoolYear|null
     */
    public function find($id): ?SchoolYear
    {
        if ($id === 'Add')
            $this->calendar = new SchoolYear();
        if (empty($id))
            $this->calendar = null;
        if (intval($id) > 0)
            $this->calendar = $this->getEntityManager()->getRepository(SchoolYear::class)->find($id);

        return $this->getSchoolYear();
    }

    /**
     * @param $cid
     */
    public function removeSchoolYearGrade($cid)
    {
        $this->setStatus('default');
        if ($cid === 'ignore')
            return ;

        if (! $this->getSchoolYear())
            return ;

        $this->findSchoolYearGrade($cid);

        $this->setStatus('warning');

        if (empty($this->calendarGrade) || ! $this->calendarGrade instanceof SchoolYearGrade) {
            $this->messageManager->add('warning', 'calendar.grades.missing.warning', ['%{calendarGrade}' => $cid]);
            return;
        }

        if ($this->calendar->getSchoolYearGrades()->contains($this->calendarGrade) && $this->calendarGradeManager->canDelete($this->calendarGrade)) {
            // Staff is NOT Deleted, but the DepartmentMember link is deleted.
            $this->calendar->removeSchoolYearGrade($this->calendarGrade);
            $this->getEntityManager()->remove($this->calendarGrade);
            $this->getEntityManager()->persist($this->calendar);
            $this->getEntityManager()->flush();

            $this->setStatus('success');
            $this->messageManager->add('success', 'calendar.calendar_grade.removed.success', ['%{calendarGrade}' => $this->calendarGrade->getFullName()]);
        } else {
            $this->setStatus('info');
            $this->messageManager->add('info', 'calendar.calendar_grade.removed.info', ['%{calendarGrade}' => $this->calendarGrade->getFullName()]);
        }
    }

    /**
     * @var null|SchoolYearGrade
     */
    private $schoolYearGrade;

    /**
     * @param $id
     * @return null|SchoolYearGrade
     */
    public function findSchoolYearGrade($id): ?SchoolYearGrade
    {
        $this->calendarGrade = $this->getEntityManager()->getRepository(SchoolYearGrade::class)->find(intval($id));

        return $this->getSchoolYearGrade();
    }

    /**
     * @return SchoolYear|null
     */
    public function refreshSchoolYearGrades(): ?SchoolYear
    {
        if (empty($this->calendar))
            return $this->calendar;

        try {
            $this->getEntityManager()->refresh($this->calendar);
            return $this->calendar->refresh();
        } catch (\Exception $e) {
            return $this->calendar;
        }
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
            $this->messageManager->add('warning', 'calendar.term.missing.warning', ['%{term}' => $cid]);
            return ;
        }

        if ($this->calendar->getTerms()->contains($this->term) && $this->term->canDelete()) {
            // Staff is NOT Deleted, but the DepartmentMember link is deleted.
            $this->calendar->removeTerm($this->term);
            $this->getEntityManager()->remove($this->term);
            $this->getEntityManager()->persist($this->calendar);
            $this->getEntityManager()->flush();

            $this->setStatus('success');
            $this->messageManager->add('success', 'calendar.term.removed.success', ['%{term}' => $this->term->getFullName()]);
        } else {
            $this->setStatus('info');
            $this->messageManager->add('info', 'calendar.term.removed.info', ['%{term}' => $this->term->getFullName()]);
        }
    }

    /**
     * @var null|Term
     */
    private $term;

    /**
     * @param $id
     * @return null|SchoolYearGrade
     */
    public function findTerm($id): ?Term
    {
        $this->term = $this->getEntityManager()->getRepository(Term::class)->find(intval($id));

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
            $this->messageManager->add('warning', 'calendar.special_day.missing.warning', ['%{specialDay}' => $cid]);
            return ;
        }

        if ($this->calendar->getSpecialDays()->contains($this->specialDay) && $this->specialDay->canDelete()) {
            // Staff is NOT Deleted, but the DepartmentMember link is deleted.
            $this->calendar->removeTerm($this->specialDay);
            $this->getEntityManager()->remove($this->specialDay);
            $this->getEntityManager()->persist($this->calendar);
            $this->getEntityManager()->flush();

            $this->setStatus('success');
            $this->messageManager->add('success', 'calendar.special_day.removed.success', ['%{specialDay}' => $this->specialDay->getFullName()]);
        } else {
            $this->setStatus('info');
            $this->messageManager->add('info', 'calendar.special_day.removed.info', ['%{specialDay}' => $this->specialDay->getFullName()]);
        }
    }

    /**
     * @var null|SpecialDay
     */
    private $specialDay;

    /**
     * @param $id
     * @return null|SchoolYearGrade
     */
    public function findSpecialDay($id): ?SpecialDay
    {
        $this->specialDay = $this->getEntityManager()->getRepository(SpecialDay::class)->find(intval($id));

        return $this->getSpecialDay();
    }

    /**
     * @return string
     */
    public function getResetScripts(): string
    {
        $request = $this->stack->getCurrentRequest();
        $xx = "manageCollection('" . $this->router->generate("calendar_grade_manage", ["id" => $request->get("id"), "cid" => "ignore"]) . "','gradeCollection', '')\n";
        $xx .= "manageCollection('" . $this->router->generate("calendar_term_manage", ["id" => $request->get("id"), "cid" => "ignore"]) . "','termCollection', '')\n";
        $xx .= "manageCollection('" . $this->router->generate("calendar_special_day_manage", ["id" => $request->get("id"), "cid" => "ignore"]) . "','specialDayCollection', '')\n";

        return $xx;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isDisplay(string $method = ''): bool
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
}