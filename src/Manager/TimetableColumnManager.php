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
class TimetableColumnManager extends TabManager implements CollectionInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = TimetableColumn::class;

    /**
     * @var array
     */
    public $tabs = [
        [
            'name' => 'details',
            'label' => 'Details',
            'include' => 'Timetable/column_details.html.twig',
            'message' => 'timetableDetailsMessage',
            'translation' => 'Timetable',
        ],
        [
            'name' => 'rows',
            'label' => 'Column Rows',
            'include' => 'Timetable/column_rows.html.twig',
            'message' => 'timetableRowMessage',
            'translation' => 'Timetable',
        ],
    ];

    /**
     * getTemplate
     *
     * @param string $name
     * @return array
     */
    public function getTemplate(string $name = 'default'): array
    {
        $template = [];

        $children = [];

        $children[] = [
            'class' => 'col-2',
            'form' => ['name'],
        ];
        $children[] = [
            'class' => 'col-2',
            'form' => ['nameShort'],
        ];
        $children[] = [
            'class' => 'col-2',
            'form' => ['timeStart'],
        ];
        $children[] = [
            'class' => 'col-2',
            'form' => [
                'timeEnd',
            ],
        ];
        $children[] = [
            'class' => 'col-2 text-right',
            'form' => [
                'type',
                'id',
                'timetableColumn',
            ],
        ];
        $actions = [
            'class' => 'col-2 text-center align-self-center',
            'buttons' => [
                [
                    'type' => 'deleteButton',
                    'mergeClass' => 'btn-sm',
                ],
                [
                    'type' => 'saveButton',
                    'mergeClass' => 'btn-sm',
                ],
            ],
            'add' => [
                'mergeClass' => 'btn-sm',
            ],
            'delete' => [
                'url' => '/timetable/column/'.$this->getEntity()->getId().'/row/{cid}/delete/',
                'url_options' => [
                    '{cid}' => 'data_id',
                ],
                'url_type' => 'json',
            ],
        ];

        $template = [
            'rows' => [
                [
                    'class' => 'small row row-striped',
                    'children' => $children,
                ],
            ],
            'actions' => $actions,
        ];

        return $template;
    }

}