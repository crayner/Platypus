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
 * Date: 26/09/2018
 * Time: 11:53
 */
namespace App\Manager;

use App\Entity\TimetableColumn;
use App\Manager\Traits\EntityTrait;
use Hillrange\Form\Util\TemplateManagerInterface;

/**
 * Class TimetableColumnManager
 * @package App\Manager
 */
class TimetableColumnManager implements TemplateManagerInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = TimetableColumn::class;

    /**
     * @var string
     */
    private $translationDomain = 'Timetable';

    /**
     * @var bool
     */
    private $locale = true;

    /**
     * getColumnTemplate
     *
     * @return array
     */
    public function getColumnTemplate(): array
    {
        return [
            'form' => [
                'url' => '/timetable/column/{id}/save/',
                'url_options' => [
                    '{id}' => 'id'
                ],
            ],
            'tabs' => [
                'details' => $this->getDetailsTab(),
                'columnRow' => $this->getColumnRowsTab(),
            ],
        ];
    }

    /**
     * getTranslationDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
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
                'panel' => [
                    'colour' => 'info',
                    'label' => 'Manage Timetable Column',
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                        [
                            'type' => 'return',
                            'url' => '/timetable/column/list/',
                            'url_type' => 'redirect',
                        ],
                    ],
                    'rows' => [
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['name' => 'row'],
                                ],
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['nameShort' => 'row'],
                                ],
                                [
                                    'class' => 'col-4 card',
                                    'form' => ['dayOfWeek' => 'row'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * getColumnRowsTab
     *
     * @return array
     */
    private function getColumnRowsTab(): array
    {
        return [
            'name' => 'rows',
            'label' => 'Column Rows',
            'container' => [
                'panel' => [
                    'colour' => 'info',
                    'label' => 'Timetable Column Rows: %{name}',
                    'label_params' => ['%{name}' => $this->getEntity()->getName()],
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                        [
                            'type' => 'return',
                            'url' => '/timetable/column/list/',
                            'url_type' => 'redirect',
                        ],
                    ],
                    'rows' => [
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'container' => [
                                        'class' => 'container',
                                        'headerRow' => [
                                            'class' => 'row row-header text-center',
                                            'columns' => [
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Name',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Abbrev.',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Start Time',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'End Time',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Column Type',
                                                ],
                                                [
                                                    'class' => 'col-2',
                                                    'label' => 'Actions',
                                                ]
                                            ],
                                        ],
                                        'collection' => [
                                            'form' => 'timetableColumnRows',
                                            'sortBy' => [
                                                'timeStart' => 'ASC',
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
                                                    'url' => '/timetable/column/'.$this->getEntity()->getId().'/row/{cid}/delete/',
                                                    'url_options' => [
                                                        '{cid}' => 'data_id',
                                                    ],
                                                    'url_type' => 'json',
                                                    'options' => [
                                                        'eid' => 'name',
                                                    ],
                                                ],
                                            ],
                                            'rows' => [
                                                [
                                                    'class' => 'small row row-striped',
                                                    'columns' => [
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['name' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['nameShort' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['timeStart' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'form' => ['timeEnd' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2 text-right',
                                                            'form' => ['type' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'hidden',
                                                            'form' => ['timetableColumn' => 'row'],
                                                        ],
                                                        [
                                                            'class' => 'col-2 text-center align-self-center',
                                                            'form' => ['id' => 'row'],
                                                            'collection_actions' => true,
                                                            'buttons' => [
                                                                'save' => [
                                                                    'mergeClass' => 'btn-sm',
                                                                    'type' => 'save',
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return bool
     */
    public function isLocale(): bool
    {
        return $this->locale;
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
}