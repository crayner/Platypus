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
class TimetableManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Timetable::class;
}