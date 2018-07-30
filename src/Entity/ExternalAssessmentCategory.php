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
 * Date: 30/06/2018
 * Time: 08:23
 */
namespace App\Entity;

/**
 * Class ExternalAssessmentCategory
 * @package App\Entity
 */
class ExternalAssessmentCategory
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
     * @return ExternalAssessmentCategory
     */
    public function setId(?int $id): ExternalAssessmentCategory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $category;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param null|string $category
     * @return ExternalAssessmentCategory
     */
    public function setCategory(?string $category): ExternalAssessmentCategory
    {
        $this->category = $category;
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
     * @return ExternalAssessmentCategory
     */
    public function setScale(?Scale $scale): ExternalAssessmentCategory
    {
        $this->scale = $scale;
        return $this;
    }

    /**
     * @var ExternalAssessment|null
     */
    private $externalAssessment;

    /**
     * @return ExternalAssessment|null
     */
    public function getExternalAssessment(): ?ExternalAssessment
    {
        return $this->externalAssessment;
    }

    /**
     * @param ExternalAssessment|null $externalAssessment
     * @return ExternalAssessmentCategory
     */
    public function setExternalAssessment(?ExternalAssessment $externalAssessment): ExternalAssessmentCategory
    {
        $this->externalAssessment = $externalAssessment;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequence;

    /**
     * @return int|null
     */
    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    /**
     * setSequence
     *
     * @param int|null $sequence
     * @return ExternalAssessmentCategory
     */
    public function setSequence(?int $sequence): ExternalAssessmentCategory
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * getScaleCategory
     *
     * @return string
     */
    public function getScaleCategory(): string
    {
        $result = '';
        $scale = $this->getScale();
        if ($scale instanceof Scale)
            $result .= $scale->getFullName() . ' - ';

        return trim($result . $this->getCategory(), ' -');
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getScaleCategory();
    }
}