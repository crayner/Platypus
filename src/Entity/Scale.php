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
 * Date: 25/06/2018
 * Time: 10:47
 */
namespace App\Entity;

/**
 * Class Scale
 * @package App\Entity
 */
class Scale
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
     * @return Scale
     */
    public function setId(?int $id): Scale
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
     * @return Scale
     */
    public function setName(?string $name): Scale
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
     * @return Scale
     */
    public function setNameShort(?string $nameShort): Scale
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var boolean
     */
    private $active;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active ? true : false;
    }

    /**
     * @param null|bool $active
     * @return AttendanceCode
     */
    public function setActive(?bool $active): Scale
    {
        $this->active = $active ? true : false;;
        return $this;
    }

    /**
     * @var string|null
     */
    private $usage;

    /**
     * @return null|string
     */
    public function getUsage(): ?string
    {
        return $this->usage;
    }

    /**
     * @param null|string $usage
     * @return Scale
     */
    public function setUsage(?string $usage): Scale
    {
        $this->usage = $usage;
        return $this;
    }

    /**
     * @var string|null
     */
    private $lowestAcceptable;

    /**
     * @return null|string
     */
    public function getLowestAcceptable(): ?string
    {
        return $this->lowestAcceptable;
    }

    /**
     * @param null|string $lowestAcceptable
     * @return Scale
     */
    public function setLowestAcceptable(?string $lowestAcceptable): Scale
    {
        $this->lowestAcceptable = $lowestAcceptable;
        return $this;
    }

    /**
     * @var boolean
     */
    private $numeric;

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->numeric ? true : false;
    }

    /**
     * @param null|bool $numeric
     * @return AttendanceCode
     */
    public function setNumeric(?bool $numeric): Scale
    {
        $this->numeric = $numeric ? true : false;;
        return $this;
    }
}