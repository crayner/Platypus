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
 * Time: 11:01
 */
namespace App\Entity\Extension;


abstract class SchoolYearTermExtension
{
    /**
     * Can Delete
     * @todo canDelete a Term
     * @return bool
     */
    public function canDelete(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        $name = '';
        if ($this->getCalendar())
            $name = $this->getCalendar()->getName();
        $name = trim($name . ' ' . $this->getName());
        return $name;
    }
}