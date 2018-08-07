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
 * Date: 4/08/2018
 * Time: 09:35
 */
namespace App\Manager;

use App\Entity\Alarm;
use App\Manager\Traits\EntityTrait;

/**
 * Class AlarmManager
 * @package App\Manager
 */
class AlarmManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Alarm::class;
}