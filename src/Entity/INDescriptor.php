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
 * Date: 19/06/2018
 * Time: 10:24
 */
namespace App\Entity;

class INDescriptor
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
     * @return INDescriptor
     */
    public function setId(?int $id): INDescriptor
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
     * @return INDescriptor
     */
    public function setName(?string $name): INDescriptor
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
     * @return INDescriptor
     */
    public function setNameShort(?string $nameShort): INDescriptor
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
     * @return INDescriptor
     */
    public function setSequence(?int $sequence): INDescriptor
    {
        $this->sequence = $sequence ?: 0;
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
     * @return INDescriptor
     */
    public function setDescription(?string $description): INDescriptor
    {
        $this->description = $description;
        return $this;
    }
}