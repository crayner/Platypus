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

use App\Entity\Timetable;
use App\Manager\Traits\EntityTrait;

/**
 * Class TimetableManager
 * @package App\Manager
 */
class TimetableManager extends TabManager
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
    public $tabs = [
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