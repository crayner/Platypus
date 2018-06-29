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
 * Date: 28/06/2018
 * Time: 17:21
 */
namespace App\Organism;

use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentField;
use App\Entity\YearGroup;

/**
 * Class PrimaryExternalAssessment
 * @package App\Organism
 */
class PrimaryExternalAssessment
{
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
     * @return PrimaryExternalAssessment
     */
    public function setExternalAssessment(?ExternalAssessment $externalAssessment): PrimaryExternalAssessment
    {
        $this->externalAssessment = $externalAssessment;
        return $this;
    }

    /**
     * @var ExternalAssessmentField|null
     */
    private $fieldSet;

    /**
     * @return mixed|null
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param mixed|null $fieldSet
     * @return PrimaryExternalAssessment
     */
    public function setFieldSet($fieldSet): PrimaryExternalAssessment
    {
        $this->fieldSet = $fieldSet;
        return $this;
    }

    /**
     * @var YearGroup|null
     */
    private $yearGroup;

    /**
     * @return null|string
     */
    public function getYearGroupName(): ?string
    {
        if (empty($this->getYearGroup()))
            return null;
        return $this->getYearGroup()->getName();
    }

    /**
     * @return YearGroup|null
     */
    public function getYearGroup(): ?YearGroup
    {
        return $this->yearGroup;
    }

    /**
     * @param YearGroup|null $yearGroup
     * @return PrimaryExternalAssessment
     */
    public function setYearGroup(?YearGroup $yearGroup): PrimaryExternalAssessment
    {
        $this->yearGroup = $yearGroup;
        return $this;
    }
}