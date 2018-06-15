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
 * Time: 16:24
 */
namespace App\Entity;

use App\Util\PhotoHelper;

class House
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return House
     */
    public function setId(?int $id): House
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return House
     */
    public function setName(?string $name): House
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nameShort;

    /**
     * @return null|string
     */
    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    /**
     * @param null|string $nameShort
     * @return House
     */
    public function setNameShort(?string $nameShort): House
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $logo;

    /**
     * @return null|string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param null|string $logo
     * @return House
     */
    public function setLogo(?string $logo): House
    {
        PhotoHelper::deletePhotoFile($this->getLogo());
        $this->logo = $logo;
        return $this;
    }

    /**
     * clearPhotos
     *
     * @return House
     */
    public function clearPhotos(): House
    {
        return $this->setLogo(null);
    }
}