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
 * Date: 21/09/2018
 * Time: 12:48
 */
namespace App\Manager;

use App\Entity\CourseClass;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use Doctrine\DBAL\Connection;

/**
 * Class CourseClassManager
 * @package App\Manager
 */
class CourseClassManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClass::class;

    protected $tabs = [
        [
            'name' => 'details',
            'label' => 'Details',
            'include' => 'Course/class_details.html.twig',
            'message' => 'courseClassDetailsMessage',
            'translation' => 'Course',
        ],
        [
            'name' => 'participants',
            'label' => 'Participants',
            'include' => 'Course/class_participants.html.twig',
            'message' => 'courseClassParticipantsMessage',
            'translation' => 'Course',
        ],
    ];
}