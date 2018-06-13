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
 * Date: 13/06/2018
 * Time: 11:04
 */
namespace App\Entity\Extension;

abstract class SchoolYearSpecialDayExtension
{
    /**
     * Can Delete
     *
     * @return bool
     */
    public function canDelete()
    {
        return true;
    }
}