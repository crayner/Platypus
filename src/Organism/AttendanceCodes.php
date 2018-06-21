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

/**
 * Class AttendanceCodes
 * @package App\Organism
 */
class AttendanceCodes
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
     * @return AttendanceCodes
     */
    public function setMultipleSettings(MultipleSettingManager $multipleSettings): AttendanceCodes
    {
        $this->multipleSettings = $multipleSettings;
        return $this;
    }

    /**
     * @var Collection
     */
    private $attendanceCodes;

    /**
     * @return Collection|null
     */
    public function getAttendanceCodes(): ?Collection
    {
        return $this->attendanceCodes;
    }

    /**
     * @param Collection $attendanceCodes
     * @return AttendanceCodes
     */
    public function setAttendanceCodes(Collection $attendanceCodes): AttendanceCodes
    {
        $this->attendanceCodes = $attendanceCodes;
        return $this;
    }
}