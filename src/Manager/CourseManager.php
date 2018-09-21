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

/**
 * Class CourseManager
 * @package App\Manager
 */
class CourseManager
{
    use EntityTrait;

    private $entityName = Course::class;
}