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
use Hillrange\Collection\React\Util\CollectionInterface;

/**
 * Class TimetableColumnManager
 * @package App\Manager
 */
class TimetableColumnManager implements FormManagerInterface
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
     * @var array
     */
    public $tabs = [
        'details' => [
            'name' => 'details',
            'label' => 'Details',
//            'include' => 'Timetable/column_details.html.twig',
            'message' => 'timetableDetailsMessage',
            'translation' => 'Timetable',
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
            'container' => [
                'panel' => [
                    'colour' => 'info',
                    'label' => 'Manage Timetable Column',
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                    ],
                ],
            ],
        ],
        'rows' => [
            'name' => 'rows',
            'label' => 'Column Rows',
//            'include' => 'Timetable/column_rows.html.twig',
            'message' => 'timetableRowMessage',
            'translation' => 'Timetable',
            'rows' => [],
            'container' => [],
        ],
    ];

    /**
     * getColumnTemplate
     *
     * @return array
     */
    public function getColumnTemplate(): array
    {
        $template = [];
        $template['useTabs'] = true;
        $template['tabs'] = $this->tabs;



        return $template;
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
}