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
 * Date: 11/06/2018
 * Time: 16:39
 */
namespace App\Entity;

class AlertLevel
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
     * @return AlertLevel
     */
    public function setId(?int $id): AlertLevel
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
     * @return AlertLevel
     */
    public function setName(?string $name): AlertLevel
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
     * @return AlertLevel
     */
    public function setNameShort(?string $nameShort): AlertLevel
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $colour;

    /**
     * @return null|string
     */
    public function getColour(): ?string
    {
        return $this->colour;
    }

    /**
     * @param null|string $colour
     * @return AlertLevel
     */
    public function setColour(?string $colour): AlertLevel
    {
        $this->colour = $colour;
        return $this;
    }

    /**
     * @var string|null
     */
    private $colourBG;

    /**
     * @return null|string
     */
    public function getColourBG(): ?string
    {
        return $this->colourBG;
    }

    /**
     * @param null|string $colourBG
     * @return AlertLevel
     */
    public function setColourBG(?string $colourBG): AlertLevel
    {
        $this->colourBG = $colourBG;
        return $this;
    }

    /**
     * @var string|null
     */
    private $description;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return AlertLevel
     */
    public function setDescription(?string $description): AlertLevel
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequenceNumber;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int|null $sequenceNumber
     * @return AlertLevel
     */
    public function setSequenceNumber(?int $sequenceNumber): AlertLevel
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

}