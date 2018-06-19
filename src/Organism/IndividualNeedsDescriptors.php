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

use App\Manager\MultipleSettingManager;
use Doctrine\Common\Collections\Collection;

class IndividualNeedsDescriptors
{
    /**
     * @var MultipleSettingManager
     */
    private $multipleSettings;

    /**
     * @return MultipleSettingManager
     */
    public function getMultipleSettings(): MultipleSettingManager
    {
        return $this->multipleSettings;
    }

    /**
     * @param MultipleSettingManager $multipleSettings
     * @return IndividualNeedsDescriptors
     */
    public function setMultipleSettings(MultipleSettingManager $multipleSettings): IndividualNeedsDescriptors
    {
        $this->multipleSettings = $multipleSettings;
        return $this;
    }

    /**
     * @var Collection
     */
    private $descriptors;

    /**
     * @return Collection|null
     */
    public function getDescriptors(): ?Collection
    {
        return $this->descriptors;
    }

    /**
     * @param Collection $descriptors
     * @return IndividualNeedsDescriptors
     */
    public function setDescriptors(Collection $descriptors): IndividualNeedsDescriptors
    {
        $this->descriptors = $descriptors;
        return $this;
    }
}