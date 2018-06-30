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
 * Time: 10:53
 */
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class ExternalAssessmentField
 * @package App\Entity
 */
class ExternalAssessmentField
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
     * setId
     *
     * @param int|null $id
     * @return ExternalAssessmentField
     */
    public function setId(?int $id): ExternalAssessmentField
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
     * setName
     *
     * @param null|string $name
     * @return ExternalAssessmentField
     */
    public function setName(?string $name): ExternalAssessmentField
    {
        $this->name = $name;
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
     * @return ExternalAssessmentField
     */
    public function setSequence(?int $sequence): ExternalAssessmentField
    {
        $this->sequence = $sequence ?: 0;
        return $this;
    }

    /**
     * @var Collection
     */
    private $yearGroups;

    /**
     * @return Collection
     */
    public function getYearGroups(): Collection
    {
        if (empty($this->yearGroups))
            $this->yearGroups = new ArrayCollection();

        if ($this->yearGroups instanceof PersistentCollection)
            $this->yearGroups->initialize();

        return $this->yearGroups;
    }

    /**
     * @param Collection|null $yearGroups
     * @return ExternalAssessmentField
     */
    public function setYearGroups(?Collection $yearGroups): ExternalAssessmentField
    {
        $this->yearGroups = $yearGroups;
        return $this;
    }

    /**
     * addYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return ExternalAssessmentField
     */
    public function addYearGroup(?YearGroup $yearGroup): ExternalAssessmentField
    {
        if (empty($yearGroup) || $this->getYearGroups()->contains($yearGroup))
            return $this;

        $this->yearGroups->add($yearGroup);
        return $this;
    }

    /**
     * removeYearGroup
     *
     * @param YearGroup|null $yearGroup
     * @return ExternalAssessmentField
     */
    public function removeYearGroup(?YearGroup $yearGroup): ExternalAssessmentField
    {
        $this->getYearGroups()->removeElement($yearGroup);
        return $this;
    }

    /**
     * __toString
     *
     * @return null|string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var ExternalAssessmentCategory|null
     */
    private $externalAssessmentCategory;

    /**
     * @return ExternalAssessmentCategory|null
     */
    public function getExternalAssessmentCategory(): ?ExternalAssessmentCategory
    {
        return $this->externalAssessmentCategory;
    }

    /**
     * @param ExternalAssessmentCategory|null $externalAssessmentCategory
     * @return ExternalAssessmentField
     */
    public function setExternalAssessmentCategory(?ExternalAssessmentCategory $externalAssessmentCategory): ExternalAssessmentField
    {
        $this->externalAssessmentCategory = $externalAssessmentCategory;
        return $this;
    }

    /**
     * @var ExternalAssessment|null
     */
    private $externalAssessment;

    /**
     * getExternalAssessment
     *
     * @return ExternalAssessment|null
     */
    public function getExternalAssessment(): ?ExternalAssessment
    {
        return $this->externalAssessment;
    }

    /**
     * setExternalAssessment
     *
     * @param ExternalAssessment|null $externalAssessment
     * @param bool $add
     * @return ExternalAssessmentField
     */
    public function setExternalAssessment(?ExternalAssessment $externalAssessment, $add = true): ExternalAssessmentField
    {
        if ($add && ! empty($externalAssessment))
            $externalAssessment->addField($this, false);
        $this->externalAssessment = $externalAssessment;
        return $this;
    }
}