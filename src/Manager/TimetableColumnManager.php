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
class TimetableColumnManager extends TabManager
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
}