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
 * Date: 24/06/2018
 * Time: 11:49
 */
namespace App\Manager;

use App\Entity\DepartmentStaff;
use App\Manager\Traits\EntityTrait;

/**
 * Class DepartmentStaffManager
 * @package App\Manager
 */
class DepartmentStaffManager
{
    use EntityTrait;

    private $entityName = DepartmentStaff::class;
}