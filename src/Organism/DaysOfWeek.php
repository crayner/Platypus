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
 * Date: 14/06/2018
 * Time: 14:20
 */

namespace App\Organism;


use Doctrine\Common\Collections\ArrayCollection;

class DaysOfWeek
{
    /**
     * @var ArrayCollection
     */
    private $days;

    /**
     * @return ArrayCollection
     */
    public function getDays(): ArrayCollection
    {
        return $this->days ?: new ArrayCollection();
    }

    /**
     * @param ArrayCollection $days
     * @return DaysOfWeek
     */
    public function setDays(?ArrayCollection $days): DaysOfWeek
    {
        $this->days = $days ?: new ArrayCollection();
        return $this;
    }

}