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

class StudentNoteCategories
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
     * @return StudentNoteCategories
     */
    public function setMultipleSettings(MultipleSettingManager $multipleSettings): StudentNoteCategories
    {
        $this->multipleSettings = $multipleSettings;
        return $this;
    }

    /**
     * @var Collection
     */
    private $categories;

    /**
     * @return Collection|null
     */
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection $categories
     * @return StudentNoteCategories
     */
    public function setCategories(Collection $categories): StudentNoteCategories
    {
        $this->categories = $categories;
        return $this;
    }
}