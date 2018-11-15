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
 * Date: 25/09/2018
 * Time: 14:25
 */
namespace App\Manager;

use App\Entity\DayOfWeek;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use App\Entity\Timetable;
use App\Entity\TimetableDay;
use App\Entity\TimetableDayDate;
use App\Manager\Traits\EntityTrait;
use App\Util\StringHelper;
use Hillrange\Form\Util\ButtonReactInterface;
use Hillrange\Form\Util\TemplateManagerInterface;

/**
 * Class TimetableManager
 * @package App\Manager
 */
class TimetableManager implements TemplateManagerInterface, ButtonReactInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Timetable::class;

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return $this->getEntity()->canDelete();
    }

    /**
     * @var array
     */
    protected $tabs = [];

    /**
     * setTabs
     *
     * @return TimetableManager
     */
    public function setTabs(): TimetableManager
    {
        if (! empty($this->tabs))
            return $this;
        $terms = [];
        foreach ($this->getTerms()->getIterator() as $term) {
            $terms[] = [
                'name' => StringHelper::safeString($term->getName()),
                'label' => $term->getName(),
                'include' => 'Timetable/term_days.html.twig',
                'message' => 'timetableTermDaysMessage',
                'with' => ['term' => $term],
                'translation' => 'Timetable',
            ];


        $tabs = [
            [
                'name' => 'details',
                'label' => 'Details',
                'include' => 'Timetable/details.html.twig',
                'message' => 'timetableDetailsMessage',
                'translation' => 'Timetable',
            ],
            [
                'name' => 'days',
                'label' => 'Days',
                'include' => 'Timetable/days.html.twig',
                'message' => 'timetableDayMessage',
                'translation' => 'Timetable',
            ],
        ];

        }

        $this->tabs = array_merge($tabs, $terms);

        return $this;
    }

    /**
     * getTerms
     *
     * @return mixed
     */
    private function getTerms()
    {
        return $this->getEntity()->getSchoolYear()->getTerms();
    }

    /**
     * @var array
     */
    private $daysOfWeek;

    /**
     * getSchoolDays
     *
     * @return array
     * @throws \Exception
     */
    public function getSchoolDays(): array
    {
        if (empty($this->daysOfWeek)) {
            $w = $this->getRepository(DayOfWeek::class)->findBy(['schoolDay' => true], ['sequence' => 'ASC']);
            $this->daysOfWeek = [];
            foreach ($w as $day)
                $this->daysOfWeek[$day_of_week = date('N', strtotime($day->getName()))] = $day;
        }
        return $this->daysOfWeek;
    }

    /**
     * getAssignedDays
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    private function getAssignedDays(SchoolYearTerm $term): array
    {
        $results = $this->getRepository(TimetableDayDate::class)->createQueryBuilder('dd')
            ->select('dd,d')
            ->leftJoin('dd.timetableDay', 'd')
            ->where('d.timetable = :timetable')
            ->setParameter('timetable', $this->getEntity())
            ->andWhere('dd.date >= :start_date')
            ->andWhere('dd.date <= :last_date')
            ->setParameter('start_date', $term->getFirstDay())
            ->setParameter('last_date', $term->getLastDay())
            ->orderBy('dd.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        if (empty($results))
            $results = $this->createDayDates($term);

        $specialDays = $this->getRepository(SchoolYearSpecialDay::class)->createQueryBuilder('sd')
            ->andWhere('sd.date >= :start_date')
            ->andWhere('sd.date <= :last_date')
            ->setParameter('start_date', $term->getFirstDay())
            ->setParameter('last_date', $term->getLastDay())
            ->orderBy('sd.date', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        $sd = [];
        foreach($specialDays as $w)
            $sd[$w->getDate()->format('Ymd')] = $w;

        foreach($results as $day)
            if (isset($sd[$day->getDate()->format('Ymd')]))
                $day->setSpecialDay($sd[$day->getDate()->format('Ymd')]);

        return $results;
    }

    /**
     * createDayDates
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    private function createDayDates(SchoolYearTerm $term): array
    {
        $results = [];

        $days = $this->getTimetableDays();

        $rotate = 0;
        $day = $term->getFirstDay();
        while ($day <= $term->getLastDay())
        {
            if ($rotate >= count($days))
                $rotate = 0;

            if (isset($this->getSchoolDays()[$day->format('N')]))
            {
                $dd = new TimetableDayDate();
                $dd->setDate(clone $day);
                $x = intval($day->format('N'));

                while ($days[$rotate]->getNormalisedDayOfWeek() !== $x) {
                    $rotate++;
                    if ($rotate >= count($days))
                        $rotate = 0;
                };

                $dd->setTimetableDay($days[$rotate]);
                $this->getEntityManager()->persist($dd);
                $results[] = $dd;

            }
            $day = date_add($day, date_interval_create_from_date_string('+1 Day'));
        }

        $this->getEntityManager()->flush();

        return $results;
    }

    /**
     * getWeeks
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    public function getWeeks(SchoolYearTerm $term): array
    {
        $firstMonday = $term->getSchoolYear()->getFirstDay();
        while($firstMonday->format('N') !== '1')
            $firstMonday = date_add($firstMonday, date_interval_create_from_date_string('+1 Day'));

        $weeks = [];

        $week = [];
        $q = 0;
        foreach($this->getAssignedDays($term) as $day)
        {
            if ($q > $day->getDayOfWeek())
            {
                $weeks[] = $week;
                $q = 0;
                $week = [];
            }
            if ($day->getDayOfWeek() >= $q)
            {
                $week[] = $day;
                $q = $day->getDayOfWeek();
            }
        }

        if (! empty($week))
        if (! empty($week))
            $weeks[] = $week;

        $w = [];
        foreach($weeks as $q=>$week)
            $w[intval(date_diff($firstMonday, $week[0]->getDate())->days / 7)] = $week;

        return $w;
    }

    /**
     * nextDayDate
     *
     * @param TimetableDayDate $dayDate
     * @return TimetableManager
     * @throws \Exception
     */
    public function nextDayDate(TimetableDayDate $dayDate): TimetableManager
    {
        $days = $this->getTimetableDays();

        $dayDate->incrementOffset(count($days));
        $this->getEntityManager()->persist($dayDate);
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * resetDayDate
     *
     * @param TimetableDayDate $dayDate
     * @return TimetableManager
     */
    public function resetDayDate(TimetableDayDate $dayDate): TimetableManager
    {
        $dayDate->setOffset(0);
        $this->getEntityManager()->persist($dayDate);
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * @var array
     */
    private $timetableDays;

    /**
     * getTimetableDays
     *
     * @return array
     * @throws \Exception
     */
    public function getTimetableDays(): array
    {
        if (empty($this->timetableDays))
            $this->timetableDays = $this->getRepository(TimetableDay::class)->findBy(['timetable' => $this->getEntity()], ['sequence' => 'ASC']);
        return $this->timetableDays;
    }

    /**
     * getTemplate
     *
     * @param string $name
     * @return array
     */
    public function getTemplate(): array
    {
        $template = [
            'form' => [
                'url' => '/timetable/'.$this->getEntity()->getId().'/edit',
            ],
            'tabs' => [
                'details' => $this->getDetailsTab(),
                'days' => $this->getDaysTab(),
            ],
        ];

        $template['tabs'] = array_merge($template['tabs'], $this->getTermTabs());

        return $template;
    }

    /**
     * getTranslationDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return 'Timetable';
    }

    /**
     * isLocale
     *
     * @return bool
     */
    public function isLocale(): bool
    {
        return true;
    }

    /**
     * getTargetDivision
     *
     * @return string
     */
    public function getTargetDivision(): string
    {
        return 'pageContent';
    }

    /**
     * getDetailsTab
     *
     * @return array
     */
    private function getDetailsTab(): array
    {
        return [
            'name' => 'details',
            'label' => 'Details',
            'container' => [
                'panel' => $this->getDetailsPanel(),
            ],
        ];
    }

    /**
     * getDetailsPanel
     *
     * @return array
     */
    private function getDetailsPanel(): array
    {
        return [
            'label' => 'Manage Timetable: %name%',
            'label_params' => [
                '%name%' => $this->getEntity()->getName(),
            ],
            'buttons' => [
                [
                    'type' => 'save',
                ],
                [
                    'type' => 'return',
                    'url' => '/timetable/list/',
                ],
            ],
            'rows' => [
                [
                    'class' => 'row',
                    'columns' => [
                        [
                            'class' => 'card col-8',
                            'rows' => [
                                [
                                    'class' => 'row',
                                    'columns' => [
                                        [
                                            'class' => 'card col-6',
                                            'form' => ['name' => 'row'],
                                        ],
                                        [
                                            'class' => 'card col-6',
                                            'form' => ['nameShort' => 'row'],
                                        ],
                                    ],
                                ],
                                [
                                    'class' => 'row',
                                    'columns' => [
                                        [
                                            'class' => 'card col-6',
                                            'form' => ['nameShortDisplay' => 'row'],
                                        ],
                                        [
                                            'class' => 'card col-6',
                                            'form' => ['active' => 'row'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'class' => 'card col-4',
                            'rows' => [
                                [
                                    'class' => 'row',
                                    'columns' => [
                                        [
                                            'class' => 'col-12',
                                            'form' => ['yearGroups' => 'row'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'class' => 'hidden',
                            'form' => ['schoolYear' => 'widget'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * getDaysTab
     *
     * @return array
     */
    private function getDaysTab(): array
    {
        return [
            'name' => 'days',
            'label' => 'Days',
            'container' => [
                'panel' => $this->getDaysPanel(),
            ],
        ];
    }

    /**
     * getDaysPanel
     *
     * @return array
     */
    private function getDaysPanel(): array
    {
        return [
            'label' => 'Timetable Days: %name%',
            'label_params' => [
                '%name%' => $this->getEntity()->getName(),
            ],
            'colour' => 'primary',
            'buttons' => [
                [
                    'type' => 'save',
                ],
                [
                    'type' => 'return',
                    'url' => '/timetable/list/',
                ],
            ],
            'collection' => [
                'form' => 'timetableDays',
                'headerRow' => [
                    'class' => 'row row-header text-center',
                    'columns' => [
                        [
                            'label' => 'Name',
                            'class' => 'col-2 align-self-center small',
                        ],
                        [
                            'class' => 'col-2 align-self-center small',
                            'label' => 'Abbrev.',
                        ],
                        [
                            'class' => 'col-2 text-center align-self-center small',
                            'label' => 'Column',
                        ],
                        [
                            'class' => 'col-2 align-self-center small',
                            'label' => 'Header Background Colour',
                        ],
                        [
                            'class' => 'col-2 align-self-center small',
                            'label' => 'Header Font Colour',
                        ],
                        [
                            'class' => 'col-2 align-self-center small',
                            'label' => 'Actions',
                        ],
                    ],
                ],
                'rows' => [
                    [
                        'class' => 'row row-striped',
                        'columns' => [
                            [
                                'class' => 'col-2 align-self-center text-small',
                                'form' => ['name' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 align-self-center text-small',
                                'form' => ['nameShort' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center text-small',
                                'form' => ['timetableColumn' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center text-small',
                                'form' => ['colour' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center text-small',
                                'form' => ['fontColour' => 'widget'],
                            ],
                            [
                                'class' => 'hidden',
                                'form' => ['id' => 'widget'],
                            ],
                            [
                                'class' => 'hidden',
                                'form' => ['timetable' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-right text-small align-self-center',
                                'collection_actions' => true,
                            ],
                        ],
                    ],
                ],
                'buttons' => [
                    'add' => [
                        'mergeClass' => 'btn-sm',
                        'type' => 'add',
                        'style' => [
                            'float' => 'right',
                        ],
                    ],
                    'delete' => [
                        'mergeClass' => 'btn-sm',
                        'type' => 'delete',
                        'url' => '/timetable/'.$this->getEntity()->getId().'/day/{cid}/delete/',
                        'url_options' => [
                            '{cid}' => 'data_id',
                        ],
                        'url_type' => 'json',
                        'collection_options' => [
                            'eid' => 'name',
                        ],
                    ],
                    'up' => [
                        'mergeClass' => 'btn-sm',
                        'type' => 'up',
                    ],
                    'down' => [
                        'mergeClass' => 'btn-sm',
                        'type' => 'down',
                    ],
                ],
                'sortBy' => true,
            ],
        ];
    }

    /**
     * getTermTabs
     *
     * @return array
     */
    private function getTermTabs(): array
    {
        $terms = [];
        foreach ($this->getTerms()->getIterator() as $term) {
            $terms[StringHelper::safeString($term->getName(), true)] = [
                'name' => StringHelper::safeString($term->getName(), true),
                'label' => $term->getName(),
                'container' => [
                    'panel' => $this->getTermPanel($term),
                ],
            ];
        }
        return $terms;
    }

    /**
     * getTermPanel
     *
     * @param $term
     * @return array
     * @throws \Exception
     */
    private function getTermPanel($term): array
    {
        return [
            'label' => 'Term Days: %tt_name% - %term%',
            'label_params' => [
                '%tt_name%'=> $this->getEntity()->getName(),
                '%term%' => $term->getName(),
            ],
            'description' => 'Click on any day to cycle to the next timetable day.  Changes to timetable days are saved immediately.',
            'colour' => 'primary',
            'buttons' => [
                [
                    'type' => 'save',
                ],
                [
                    'type' => 'return',
                    'url' => '/timetable/list/',
                ],
            ],
            'headerRow' => [
                'class' => 'row',
                'columns' => [
                    [
                        'class' => 'col-9 offset-3',
                        'rows' => [
                            [
                                'class' => 'row row-header small',
                                'columns' => $this->getSchoolDayHeaders(),
                            ],
                        ],
                    ],
                ],
            ],
            'rows' => $this->getWeeksOfTerm($term),
        ];
    }

    /**
     * getSchoolDayHeaders
     *
     * @return array
     * @throws \Exception
     */
    private function getSchoolDayHeaders(): array
    {
        $columns = [];
        foreach($this->getSchoolDays() as $day)
        {
            $column = [
                'class' => 'col-2 text-center small align-self-center',
                'label' => 'school_day_header',
                'label_params' => ['school_day_header' => '<div className="small font-weight-bold" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' . $day->getName() . '</div><div className="small text-muted">'.$day->getNameShort().'</div>'],
            ];
            $columns[] = $column;
        }
        return $columns;
    }

    /**
     * getWeeksOfTerm
     *
     * @param SchoolYearTerm $term
     * @return array
     */
    private function getWeeksOfTerm(SchoolYearTerm $term): array
    {
        foreach($this->getWeeks($term) as $value=>$week)
        {
            $row = [
                'class' => 'row',
                'columns' => [
                    [
                        'class' => 'col-3 card',
                        'rows' => [
                            [
                                'class' => 'row-header row',
                                'columns' => [
                                    [
                                        'class' => 'col-12 card text-center small align-self-center',
                                        'style' => ['height' => '100px', 'display' => 'flex', 'margin' => 'auto'],
                                        'label' => 'week_header',
                                        'label_params' => ['week_header' => $this->getWeekHeader($week)],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'class' => 'col-9 card',
                        'rows' => [
                            [
                                'class' => 'row small',
                                'columns' => $this->getDayColumns($week,$term),
                            ],
                        ],
                    ],
                ],
            ];
            $weeks[] = $row;
        }

        return $weeks;
    }

    /**
     * getWeekHeader
     *
     * @param $week
     * @return string
     */
    private function getWeekHeader(array $week): string
    {
        $first =  reset($week);
        $last = end($week);
        return '<span className="small font-weight-bold" style="margin: auto; text-align: center;">' . $first->getDate()->format($this->getSettingManager()->get('date.format.long')) . '<br/>---<br/>' . $last->getDate()->format($this->getSettingManager()->get('date.format.long')).'</span>';
    }

    /**
     * getDayColumns
     *
     * @param $week
     * @return array
     */
    private function getDayColumns(array $week, SchoolYearTerm $term): array
    {
        $columns = [];
        $offset = 1;
        foreach($week as $day){
            for($i=0; $i<=7; $i++){
                if ($day->getDayOfWeek() > $offset)
                {
                    $offset++;
                    $column = [
                        'class' => 'col-2 flex-container',
                        'label' => '',
                    ];
                    $columns[] = $column;
                }
            }

            $offset++;
            $columns[] = $this->getDayDetails($day,$term);
        }
        return $columns;
    }

    /**
     * getDayDetails
     *
     * @return array
     */
    private function getDayDetails(TimetableDayDate $day, SchoolYearTerm $term): array
    {
        $alert = '';
        if ($day->getType() === 'school_closure') $alert = ' alert-danger';
        if ($day->getType() === 'school_alter') $alert = ' alert-warning';

        $column = [
            'class' => 'col-2 card align-self-center text-center' . $alert,
            'style' => ['minHeight' => '100px'],
            'onClick' => [
                'url' => '/timetable/'.$this->getEntity()->getId().'/term/'.$term->getId().'/day/date/'.$day->getId().'/increment/',
            ],
            'label' => 'day_label',
            'label_params' => ['day_label' => $this->getDayLabel($day)],
        ];

        if ($day->getType() !== ''){
            $button = [
                'type' => 'misc',
                'icon' => ['far','calendar-check'],
                'title' => 'name',
                'title_params' => ['name' => $day->getSpecialDay()->getDescription() ?: $day->getSpecialDay()->getName()],
                'colour' => 'transparent',
                'style' => ['padding' => '0 1px', 'float: right'],
            ];
            $column['buttons'][] = $button;
        }

        if ($day->getOffset() > 0)
        {
            $button = [
                'type' => 'misc',
                'icon' => ['fas','undo'],
                'title' => 'Undo changes to timetable day by clicking here.',
                'colour' => 'transparent',
                'style' => ['padding' => '0 1px', 'float: right'],
                'url' => '/timetable/'.$this->getEntity()->getId().'/term/'.$term->getId().'/day/date/'.$day->getId().'/reset/',
            ];
            $column['buttons'][] = $button;
        }

        return $column;
    }

    /**
     * getDayLabel
     *
     * @param $day
     * @return string
     */
    private function getDayLabel(TimetableDayDate $day): string
    {
        $label = '';
        if ($day->getType() !== '') {
            $label .= $day->getSpecialDay()->getName();
        } else {
            $timetableDay = $day->getTimetableDay($this->getRepository(TimetableDay::class));
            $label .= $timetableDay->getName();
            if (! empty($timetableDay->getNameShort())) {
                $label .= '<br/><span class="small" style="background-color: ' . $timetableDay->getColour() . '; color: ' . $timetableDay->getFontColour() . ';" >(' . $timetableDay->getNameShort() . ')</span>';
            }
        }
        $label .= '<span class="small">' . $day->getDate()->format($this->getSettingManager()->get('date.format.short')) . '</span>';

        return $label ;
    }

    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
     * @param SettingManager $settingManager
     * @return TimetableManager
     */
    public function setSettingManager(SettingManager $settingManager): TimetableManager
    {
        $this->settingManager = $settingManager;
        return $this;
    }

    /**
     * isSchoolDay
     *
     * @param null $date
     * @param string $timezone
     * @return bool
     * @throws \Exception
     */
    public function isSchoolDay($date = null, $timezone = 'UTC'): bool
    {
        if (is_null($date))
            $date = new \DateTime('now', new \DateTimeZone($timezone));
        if (! $date instanceof \DateTime)
            $date = new \DateTime($date, new \DateTimeZone($timezone));

        foreach($this->getSchoolDays() as $day)
        {
            if ($day->getName() === $date->format('l') || $day->getNameShort() === $date->format('D'))
                return $day->isSchoolDay();
        }
        return false;
    }
}
