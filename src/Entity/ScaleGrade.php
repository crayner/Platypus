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
 * Date: 27/06/2018
 * Time: 08:50
 */
namespace App\Entity;

/**
 * Class ScaleGrade
 * @package App\Entity
 */
class ScaleGrade
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
     * @return ScaleGrade
     */
    public function setId(?int $id): ScaleGrade
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $value;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     * @return ScaleGrade
     */
    public function setValue(?string $value): ScaleGrade
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @var string|null
     */
    private $descriptor;

    /**
     * @return null|string
     */
    public function getDescriptor(): ?string
    {
        return $this->descriptor;
    }

    /**
     * @param null|string $descriptor
     * @return ScaleGrade
     */
    public function setDescriptor(?string $descriptor): ScaleGrade
    {
        $this->descriptor = $descriptor;
        return $this;
    }

    /**
     * @var integer
     */
    private $sequence;

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return intval($this->sequence);
    }

    /**
     * @param int $sequence
     * @return ScaleGrade
     */
    public function setSequence(?int $sequence): ScaleGrade
    {
        $this->sequence = intval($sequence);
        return $this;
    }

    /**
     * @var boolean
     */
    private $default;

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default ? true : false ;
    }

    /**
     * @param bool $default
     * @return ScaleGrade
     */
    public function setDefault(bool $default): ScaleGrade
    {
        $this->default = $default ? true : false ;
        return $this;
    }

    /**
     * @var Scale|null
     */
    private $scale;

    /**
     * @return Scale|null
     */
    public function getScale(): ?Scale
    {
        return $this->scale;
    }

    /**
     * @param Scale|null $scale
     * @return ScaleGrade
     */
    public function setScale(?Scale $scale, $add = true): ScaleGrade
    {
        if (! empty($scale) && $add)
            $scale->addGrade($this, false);

        $this->scale = $scale;
        return $this;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue() ?: '';
    }
}