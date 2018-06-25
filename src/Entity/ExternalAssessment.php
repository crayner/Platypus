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
 * Time: 10:13
 */

namespace App\Entity;


class ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setId(?int $id): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setName(?string $name): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setNameShort(?string $nameShort): ExternalAssessment
    {
        $this->nameShort = $nameShort;
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
     * @return ExternalAssessment
     */
    public function setDescription(?string $description): ExternalAssessment
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var string|null
     */
    private $website;

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param null|string $website
     * @return RollGroup
     */
    public function setWebsite(?string $website): ExternalAssessment
    {
        $this->website = $website;
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
    public function setActive(?bool $active): ExternalAssessment
    {
        $this->active = $active ? true : false;;
        return $this;
    }

    /**
     * @var boolean
     */
    private $allowFileUpload;

    /**
     * @return bool
     */
    public function isAllowFileUpload(): bool
    {
        return $this->allowFileUpload ? true : false;
    }

    /**
     * @param null|bool $allowFileUpload
     * @return AttendanceCode
     */
    public function setAllowFileUpload(?bool $allowFileUpload): ExternalAssessment
    {
        $this->allowFileUpload = $allowFileUpload ? true : false;;
        return $this;
    }
}