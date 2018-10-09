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
 * Date: 9/10/2018
 * Time: 15:06
 */
namespace App\Manager;

use App\Entity\CourseClassPerson;
use App\Manager\Traits\EntityTrait;

/**
 * Class CourseClassPersonManager
 * @package App\Manager
 */
class CourseClassPersonManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClassPerson::class;
}