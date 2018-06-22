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

class Facility
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
     * @return Facility
     */
    public function setId(?int $id): Facility
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
     * @return Facility
     */
    public function setName(?string $name): Facility
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return Facility
     */
    public function setType(?string $type): Facility
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @var integer
     */
    private $capacity;

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return intval($this->capacity);
    }

    /**
     * @param int|null $capacity
     * @return Facility
     */
    public function setCapacity(?int $capacity): Facility
    {
        $this->capacity = intval($capacity);
        return $this;
    }

    /**
     * @var boolean
     */
    private $computer;

    /**
     * @return bool
     */
    public function isComputer(): bool
    {
        return $this->computer ? true : false;
    }

    /**
     * @param bool $computer
     * @return Facility
     */
    public function setComputer(bool $computer): Facility
    {
        $this->computer = $computer ? true : false ;
        return $this;
    }

    /**
     * @var integer
     */
    private $studentComputers;

    /**
     * @return int
     */
    public function getStudentComputers(): int
    {
        return intval($this->studentComputers);
    }

    /**
     * @param int|null $studentComputers
     * @return Facility
     */
    public function setStudentComputers(?int $studentComputers): Facility
    {
        $this->studentComputers = intval($studentComputers);
        return $this;
    }

    /**
     * @var boolean
     */
    private $projector;

    /**
     * @return bool
     */
    public function isProjector(): bool
    {
        return $this->projector ? true : false;
    }

    /**
     * @param bool $projector
     * @return Facility
     */
    public function setProjector(bool $projector): Facility
    {
        $this->projector = $projector ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $tv;

    /**
     * @return bool
     */
    public function isTv(): bool
    {
        return $this->tv ? true : false;
    }

    /**
     * @param bool $tv
     * @return Facility
     */
    public function setTv(bool $tv): Facility
    {
        $this->tv = $tv ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $dvd;

    /**
     * @return bool
     */
    public function isDvd(): bool
    {
        return $this->dvd ? true : false;
    }

    /**
     * @param bool $dvd
     * @return Facility
     */
    public function setDvd(bool $dvd): Facility
    {
        $this->dvd = $dvd ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $hifi;

    /**
     * @return bool
     */
    public function isHifi(): bool
    {
        return $this->hifi ? true : false;
    }

    /**
     * @param bool $hifi
     * @return Facility
     */
    public function setHifi(bool $hifi): Facility
    {
        $this->hifi = $hifi ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $speakers;

    /**
     * @return bool
     */
    public function isSpeakers(): bool
    {
        return $this->speakers ? true : false;
    }

    /**
     * @param bool $speakers
     * @return Facility
     */
    public function setSpeakers(bool $speakers): Facility
    {
        $this->speakers = $speakers ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $iwb;

    /**
     * @return bool
     */
    public function isIwb(): bool
    {
        return $this->iwb ? true : false;
    }

    /**
     * @param bool $iwb
     * @return Facility
     */
    public function setIwb(bool $iwb): Facility
    {
        $this->iwb = $iwb ? true : false ;
        return $this;
    }

    /**
     * @var string|null
     */
    private $phoneInt;

    /**
     * @return null|string
     */
    public function getPhoneInt(): ?string
    {
        return $this->phoneInt;
    }

    /**
     * @param null|string $phoneInt
     * @return Facility
     */
    public function setPhoneInt(?string $phoneInt): Facility
    {
        $this->phoneInt = $phoneInt;
        return $this;
    }

    /**
     * @var string|null
     */
    private $phoneExt;

    /**
     * @return null|string
     */
    public function getPhoneExt(): ?string
    {
        return $this->phoneExt;
    }

    /**
     * @param null|string $phoneExt
     * @return Facility
     */
    public function setPhoneExt(?string $phoneExt): Facility
    {
        $this->phoneExt = $phoneExt;
        return $this;
    }

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return Facility
     */
    public function setComment(?string $comment): Facility
    {
        $this->comment = $comment;
        return $this;
    }
}