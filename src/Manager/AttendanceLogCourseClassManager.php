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
 * Date: 15/11/2018
 * Time: 11:56
 */
namespace App\Manager;

use App\Entity\AttendanceLogCourseClass;
use App\Manager\Traits\EntityTrait;

/**
 * Class AttendanceLogCourseClassManager
 * @package App\Manager
 */
class AttendanceLogCourseClassManager
{
    use EntityTrait;

    private $entityName = AttendanceLogCourseClass::class;
}