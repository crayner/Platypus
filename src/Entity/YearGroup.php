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
 * Time: 16:47
 */
namespace App\Entity;

class YearGroup
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
     * @return YearGroup
     */
    public function setId(?int $id): YearGroup
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
     * @return YearGroup
     */
    public function setName(?string $name): YearGroup
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
     * @return YearGroup
     */
    public function setNameShort(?string $nameShort): YearGroup
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequence;

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence ?: 0;
    }

    /**
     * setSequence
     *
     * @param int|null $sequence
     * @return YearGroup
     */
    public function setSequence(?int $sequence): YearGroup
    {
        $this->sequence = $sequence ?: 0;
        return $this;
    }
}