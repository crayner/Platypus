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
 * Date: 13/08/2018
 * Time: 10:07
 */
namespace App\Entity;

/**
 * Class Staff
 * @package App\Entity
 */
class Staff
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * getStaffTypeList
     *
     * @return array
     */
    public static function getStaffTypeList(): array
    {
        return self::$staffTypeList;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Staff
     */
    public function setId(?int $id): Staff
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var null|Person
     */
    private $person;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return Staff
     */
    public function setPerson(?Person $person): Staff
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var boolean
     */
    private $smartWorkflowHelp;

    /**
     * @return bool
     */
    public function isSmartWorkflowHelp(): bool
    {
        return $this->smartWorkflowHelp ? true : false ;
    }

    /**
     * @param bool|null $smartWorkflowHelp
     * @return Staff
     */
    public function setSmartWorkflowHelp(?bool $smartWorkflowHelp): Staff
    {
        $this->smartWorkflowHelp = $smartWorkflowHelp ? true : false;
        return $this;
    }

    /**
     * Staff constructor.
     */
    public function __construct()
    {
        $this->smartWorkflowHelp = true;
    }

    /**
     * @var array
     */
    private static $staffTypeList = [
        'teaching',
        'support',
        'administrator',
        'ancillary',
        'finance',
    ];

    /**
     * @var string|null
     */
    private $staffType;

    /**
     * @return null|string
     */
    public function getStaffType(): ?string
    {
        return $this->staffType;
    }

    /**
     * @param null|string $staffType
     * @return Staff
     */
    public function setStaffType(?string $staffType): Staff
    {
        $this->staffType = $staffType;
        return $this;
    }

    /**
     * @var string|null
     */
    private $jobTitle;

    /**
     * @return null|string
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param null|string $jobTitle
     * @return Staff
     */
    public function setJobTitle(?string $jobTitle): Staff
    {
        $this->jobTitle = $jobTitle;
        return $this;
    }

    /**
     * @var boolean
     */
    private $firstAidQualified;

    /**
     * @return bool
     */
    public function isFirstAidQualified(): bool
    {
        return $this->firstAidQualified ? true : false ;
    }

    /**
     * @param bool|null $firstAidQualified
     * @return Staff
     */
    public function setFirstAidQualified(?bool $firstAidQualified): Staff
    {
        $this->firstAidQualified = $firstAidQualified ? true : false;
        return $this;
    }

    /**
     * @var string|null
     */
    private $countryOfOrigin;

    /**
     * @return null|string
     */
    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    /**
     * @param null|string $countryOfOrigin
     * @return Staff
     */
    public function setCountryOfOrigin(?string $countryOfOrigin): Staff
    {
        $this->countryOfOrigin = $countryOfOrigin;
        return $this;
    }

    /**
     * @var string|null
     */
    private $qualifications;

    /**
     * @return null|string
     */
    public function getQualifications(): ?string
    {
        return $this->qualifications;
    }

    /**
     * @param null|string $qualifications
     * @return Staff
     */
    public function setQualifications(?string $qualifications): Staff
    {
        $this->qualifications = $qualifications;
        return $this;
    }

    /**
     * @var string|null
     */
    private $biography;

    /**
     * @return null|string
     */
    public function getBiography(): ?string
    {
        return $this->biography;
    }

    /**
     * @param null|string $biography
     * @return Staff
     */
    public function setBiography(?string $biography): Staff
    {
        $this->biography = $biography;
        return $this;
    }

    /**
     * @var string|null
     */
    private $biographicalGrouping;

    /**
     * @return null|string
     */
    public function getBiographicalGrouping(): ?string
    {
        return $this->biographicalGrouping;
    }

    /**
     * @param null|string $biographicalGrouping
     * @return Staff
     */
    public function setBiographicalGrouping(?string $biographicalGrouping): Staff
    {
        $this->biographicalGrouping = $biographicalGrouping;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $biographicalGroupingPriority;

    /**
     * @return int|null
     */
    public function getBiographicalGroupingPriority(): ?int
    {
        return $this->biographicalGroupingPriority;
    }

    /**
     * @param int|null $biographicalGroupingPriority
     * @return Staff
     */
    public function setBiographicalGroupingPriority(?int $biographicalGroupingPriority): Staff
    {
        $this->biographicalGroupingPriority = $biographicalGroupingPriority;
        return $this;
    }

    /**
     * @var string|null
     */
    private $initials;

    /**
     * @return null|string
     */
    public function getInitials(): ?string
    {
        return $this->initials;
    }

    /**
     * @param null|string $initials
     * @return Staff
     */
    public function setInitials(?string $initials): Staff
    {
        $this->initials = $initials;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $firstAidExpiry;

    /**
     * @return \DateTime|null
     */
    public function getFirstAidExpiry(): ?\DateTime
    {
        return $this->firstAidExpiry;
    }

    /**
     * @param \DateTime|null $firstAidExpiry
     * @return Staff
     */
    public function setFirstAidExpiry(?\DateTime $firstAidExpiry): Staff
    {
        $this->firstAidExpiry = $firstAidExpiry;
        return $this;
    }
}