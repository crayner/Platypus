<?php
namespace App\Organism;

use App\Entity\DayOfWeek;
use App\Entity\TimetableDay;
use App\Manager\SettingManager;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class Day
 * @package App\Organism
 */
class Day
{
	/**
	 * @var \DateTime
	 */
	protected $date;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @var SettingManager
	 */
	protected $settingManager;

	/**
	 * @var int
	 */
	private $firstDayofWeek;

	/**
	 * @var int
	 */
	private $lastDayofWeek;

	/**
	 * @var int|null
	 */
	private $weekNumber = null;

	/**
	 * @var bool
	 */
	private $termBreak = false;

	/**
	 * @var  bool
	 */
	private $closed;

	/**
	 * @var  bool
	 */
	private $special;

	/**
	 * @var null
	 */
	private $prompt;

    /**
     * @var array
     */
	private $daysOfWeek;

    /**
     * @var string
     */
    private $dayLong;

    /**
     * @var string
     */
    private $dayShort;

	/**
	 * Day constructor.
	 *
	 * @param \DateTime               $date
	 * @param SettingManager          $sm
	 */
	public function __construct(\DateTime $date, SettingManager $settingManager, int $weeks)
	{
		$this->settingManager = $settingManager;
		$this->parameters     = [];
		$this->date           = clone $date;
        $this->day            = $date->format($this->settingManager->get('date.format.long'));
        $this->dayLong       = $date->format($this->settingManager->get('date.format.long'));
        $this->dayShort      = $date->format($this->settingManager->get('date.format.short'));
		$this->firstDayofWeek = $this->settingManager->get('firstDayofWeek', 'Monday') == 'Sunday' ? 7 : 1;
		$this->lastDayofWeek  = $this->settingManager->get('firstDayofWeek', 'Monday') == 'Sunday' ? 6 : 7;
        $this->special = false;
        $this->closed = false;
        $this->daysOfWeek = $this->getSettingManager()->getEntityManager()->getRepository(DayOfWeek::class)->findBy([], ['sequence' => 'ASC']);
		$this->setWeekNumber($weeks);
	}

	/**
	 * @param int $weekNumber
	 *
	 * @return Week
	 */
	public function setWeekNumber(int $weekNumber): Day
	{
		$this->weekNumber = $weekNumber;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getWeekNumber(): int
	{
		return $this->weekNumber;
	}

    /**
     * getDate
     *
     * @return \DateTime
     */
	public function getDate()
	{
		return $this->date;
	}

    /**
     * getNumber
     *
     * @return string
     */
	public function getNumber()
	{
		return $this->date->format('j');
	}

    /**
     * isFirstInWeek
     *
     * @return bool
     */
	public function isFirstInWeek()
	{
		return $this->date->format('N') == $this->firstDayofWeek;
	}

    /**
     * isLastInWeek
     *
     * @return bool
     */
	public function isLastInWeek()
	{
		return $this->date->format('N') == $this->lastDayofWeek;
	}

    /**
     * isInWeek
     *
     * @param Week $week
     * @return bool
     */
	public function isInWeek(Week $week)
	{
		return $this->date->format('W') == $week->getNumber();
	}

    /**
     * isInMonth
     *
     * @param Month $month
     * @return bool
     */
	public function isInMonth(Month $month)
	{
		return (($this->date->format('n') == $month->getNumber())
			&& ($this->date->format('Y') == $month->getYear()));
	}

    /**
     * isInYear
     *
     * @param Year $year
     * @return bool
     */
	public function isInYear(Year $year)
	{
		return $this->date->format('Y') == $year;
	}

	public function setParameter($key, $value)
	{
		$this->parameters[$key] = $value;
	}

	public function getParameter($key)
	{
		return key_exists($key, $this->parameters) ? $this->parameters[$key] : null;
	}

    /**
     * @var boolean
     */
	private $schoolDay;

    /**
     * isSchoolDay
     *
     * @return bool
     */
	public function isSchoolDay(): bool
	{
	    $dayOfWeek = intval($this->getDate()->format('N'));
        foreach($this->getDaysOfWeek() as $day)
            if ($dayOfWeek === $day->getNormalisedDayOfWeek())
                return $this->schoolDay = $day->isSchoolDay();

		return $this->schoolDay = false ;
	}

	/**
	 * @param bool $schoolDay
	 *
	 * @return Day
	 */
	public function setSchoolDay(bool $schoolDay): Day
	{
		$this->schoolDay = $schoolDay;

		return $this;
	}

    /**
     * isTermBreak
     *
     * @return bool
     */
	public function isTermBreak(): bool
	{
		return $this->termBreak ? true : false ;
	}

    /**
     * setTermBreak
     *
     * @param bool $termBreak
     * @return Day
     */
	public function setTermBreak(bool $termBreak): Day
	{
		$this->termBreak = (bool) $termBreak;

		return $this;
	}

    /**
     * isClosed
     *
     * @return bool
     */
	public function isClosed(): bool
	{
		return $this->closed ? true : false ;
	}

    /**
     * setClosed
     *
     * @param bool $value
     * @param string $prompt
     */
	public function setClosed(bool $value, string $prompt)
	{
		$this->closed = (bool) $value;
		$this->prompt = $prompt;
	}

	/**
	 * @return bool
	 */
	public function isSpecial(): bool
	{
		return $this->special ? true : false ;
	}

    /**
     * setSpecial
     *
     * @param bool $value
     * @param string $prompt
     */
	public function setSpecial(bool $value, string $prompt)
	{
		$this->special = (bool) $value;
		$this->prompt  = $prompt;
	}

    /**
     * getPrompt
     *
     * @return null|string
     */
	public function getPrompt(): ?string
	{
		return $this->prompt;
	}

    /**
     * getFirstDayofWeek
     *
     * @return int
     */
    public function getFirstDayofWeek(): int
    {
        return $this->firstDayofWeek;
    }

    /**
     * @return array
     */
    public function getDaysOfWeek(): array
    {
        return $this->daysOfWeek;
    }

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    public function __toObject(): object
    {
        return json_decode($this->serialise());
    }

    /**
     * serialise
     *
     * @return string
     */
    public function serialise(): string
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer('Y-m-d H:i:s'), new ObjectNormalizer()];
        $serialiser = new Serializer($normalizers, $encoders);

        return $serialiser->serialize($this, 'json', ['attributes' => ['date', 'schoolDay', 'parameters', 'firstDayofWeek', 'lastDayofWeek', 'weekNumber', 'closed', 'special', 'prompt', 'dayLong', 'dayShort', 'termBreak', 'daysOfWeek' => ['id','name','nameShort', 'sequence','schoolDay','schoolOpen','schoolStart','schoolEnd','schoolClose'], 'timetableDay' => ['id','colour','fontColour','name','nameShort']]]);
    }

    /**
     * deSerialise
     *
     * @param string $data
     * @return object
     */
    public function deSerialise(string $data): Day
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer('Y-m-d H:i:s'), new ObjectNormalizer()];
        $serialiser = new Serializer($normalizers, $encoders);

        $serialiser->deserialize($data, Day::class, 'json');

        return $this;
    }

    /**
     * getDayLong
     *
     * @return string
     */
    public function getDayLong(): string
    {
        return $this->dayLong;
    }

    /**
     * getDayShort
     *
     * @return string
     */
    public function getDayShort(): string
    {
        return $this->dayShort;
    }

    /**
     * setDayLong
     *
     * @param string $dayLong
     * @return Day
     */
    public function setDayLong(string $dayLong): Day
    {
        $this->dayLong = $dayLong;
        return $this;
    }

    /**
     * setDayShort
     *
     * @param string $dayShort
     * @return Day
     */
    public function setDayShort(string $dayShort): Day
    {
        $this->dayShort = $dayShort;
        return $this;
    }

    /**
     * @var TimetableDay
     */
    private $timetableDay;

    /**
     * getTimetableDay
     *
     * @return TimetableDay|null
     */
    public function getTimetableDay(): ?TimetableDay
    {
        return $this->timetableDay;
    }

    /**
     * setTimetableDay
     *
     * @param TimetableDay|null $timetableDay
     * @return Day
     */
    public function setTimetableDay(?TimetableDay $timetableDay): Day
    {
        $this->timetableDay = $timetableDay;
        return $this;
    }
}
