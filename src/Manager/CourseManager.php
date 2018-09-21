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
 * Date: 20/09/2018
 * Time: 21:39
 */
namespace App\Manager;

use App\Entity\Course;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class CourseManager
 * @package App\Manager
 */
class CourseManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Course::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'details',
            'label' => 'Details',
            'include' => 'Course/details.html.twig',
            'message' => 'courseDetailsMessage',
            'translation' => 'Course',
        ],
        [
            'name' => 'description',
            'label' => 'Description (Blurb)',
            'include' => 'Course/description.html.twig',
            'message' => 'courseDescriptionMessage',
            'translation' => 'Course',
        ],
        [
            'name' => 'classes',
            'label' => 'Classes',
            'include' => 'Course/classes.html.twig',
            'message' => 'courseClassesMessage',
            'translation' => 'Course',
        ],
    ];
}