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
use Hillrange\Form\Util\TemplateManagerInterface;

/**
 * Class TimetableManager
 * @package App\Manager
 */
class TimetableManager implements TemplateManagerInterface
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

        $x = array_search($dayDate->getTimetableDay(), $days);

        $x++;
        if ($x >= count($days))
            $x=0;
        $dayDate->setTimetableDay($days[$x]);
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
                'url' => '/timetable/{id}/edit',
                'url_options' => [
                    '{id}' => 'id'
                ],
            ],
            'tabs' => [
                'details' => $this->getDetailsTab(),
                'days' => $this->getDaysTab(),
            ],
        ];

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

        /*
        {% include 'Default/panelStart.html.twig' with {header: 'Manage Timetable: %name%', name: manager.entity.name, transDomain: 'Timetable', panelParagraph: 'The timetable is locked to the school year in which you are currently working.', panelStyle: 'info'} %}
<div class="row">
    <div class="col-8 card">
        <div class="row">
            <div class="col-6 card">
                {{ form_row(form.name) }}
            </div>
            <div class="col-6 card">
                {{ form_row(form.nameShort) }}
            </div>
        </div>
        <div class="row">
            <div class="col-6 card">
                {{ form_row(form.nameShortDisplay) }}
            </div>
            <div class="col-6 card">
                {{ form_row(form.active) }}
            </div>
        </div>
    </div>
    <div class="col-4 card">
        <div class="row">
            <div class="col-12">
                {{ form_row(form.yearGroups) }}
                {{ form_row(form.schoolYear, {value: getCurrentSchoolYear().id}) }}
            </div>
        </div>
    </div>
</div>
{% include 'Default/panelEnd.html.twig' %}
        */
    }

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
            ],
            'collection' => [
                'form' => 'timetableDays',
                'headerRow' => [
                    'class' => 'row row-header text-center small',
                    'columns' => [
                        [
                            'label' => 'Name',
                            'class' => 'col-2 align-self-center',
                        ],
                        [
                            'class' => 'col-2 align-self-center',
                            'label' => 'Abbrev.',
                        ],
                        [
                            'class' => 'col-2 text-center align-self-center',
                            'label' => 'Column',
                        ],
                        [
                            'class' => 'col-2 align-self-center',
                            'label' => 'Header Background Colour',
                        ],
                        [
                            'class' => 'col-2 align-self-center',
                            'label' => 'Header Font Colour',
                        ],
                        [
                            'class' => 'col-2 align-self-center',
                            'label' => 'Actions',
                        ],
                    ],
                ],
                'rows' => [
                    [
                        'class' => 'row row-striped small',
                        'columns' => [
                            [
                                'class' => 'col-2 align-self-center',
                                'form' => ['name' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 align-self-center',
                                'form' => ['nameShort' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center',
                                'form' => ['timetableColumn' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center',
                                'form' => ['colour' => 'widget'],
                            ],
                            [
                                'class' => 'col-2 text-center align-self-center',
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
                        'options' => [
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

}
