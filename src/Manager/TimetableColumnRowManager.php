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
 * Time: 15:03
 */

namespace App\Manager;


use App\Entity\TimetableColumnRow;
use App\Manager\Traits\EntityTrait;

class TimetableColumnRowManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = TimetableColumnRow::class;
}