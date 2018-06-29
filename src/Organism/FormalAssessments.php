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
 * Date: 28/06/2018
 * Time: 17:02
 */
namespace App\Organism;

use App\Manager\MultipleSettingManager;
use Doctrine\Common\Collections\Collection;

/**
 * Class FormalAssessments
 * @package App\Organism
 */
class FormalAssessments
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
     * @return FormalAssessments
     */
    public function setMultipleSettings(MultipleSettingManager $multipleSettings): FormalAssessments
    {
        $this->multipleSettings = $multipleSettings;
        return $this;
    }

    /**
     * @var Collection
     */
    private $assessments;

    /**
     * @return Collection
     */
    public function getAssessments(): Collection
    {
        return $this->assessments;
    }

    /**
     * @param Collection $assessments
     * @return FormalAssessments
     */
    public function setAssessments(Collection $assessments): FormalAssessments
    {
        $this->assessments = $assessments;
        return $this;
    }
}