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
 * Date: 18/06/2018
 * Time: 17:02
 */
namespace App\Organism;

use Doctrine\Common\Collections\Collection;

class AlertLevels
{
    /**
     * @var Collection
     */
    private $alertLevels;

    /**
     * @return Collection|null
     */
    public function getAlertLevels(): ?Collection
    {
        return $this->alertLevels;
    }

    /**
     * @param Collection $alertLevels
     * @return AlertLevels
     */
    public function setAlertLevels(Collection $alertLevels): AlertLevels
    {
        $this->alertLevels = $alertLevels;
        return $this;
    }
}