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
 * Date: 22/06/2018
 * Time: 11:47
 */
namespace App\Organism;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class FileExtensions
 * @package App\Organism
 */
class FileExtensions
{
    /**
     * @var ArrayCollection
     */
    private $fileExtensions;

    /**
     * @return ArrayCollection|null
     */
    public function getFileExtensions(): ?ArrayCollection
    {
        return $this->fileExtensions;
    }

    /**
     * @param ArrayCollection $fileExtensions
     * @return FileExtensions
     */
    public function setFileExtensions(ArrayCollection $fileExtensions): FileExtensions
    {
        $this->fileExtensions = $fileExtensions;
        return $this;
    }
}