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
 * Date: 20/09/2018
 * Time: 14:47
 */

namespace App\Entity;


class StudentEnrolment
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
     * @return StudentEnrolment
     */
    public function setId(?int $id): StudentEnrolment
    {
        $this->id = $id;
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
     * @param int|null $sequence
     * @return StudentEnrolment
     */
    public function setSequence(?int $sequence): StudentEnrolment
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $student;

    /**
     * @return Person|null
     */
    public function getStudent(): ?Person
    {
        return $this->student;
    }

    /**
     * @param Person|null $student
     * @return StudentEnrolment
     */
    public function setStudent(?Person $student, $add = true): StudentEnrolment
    {
        if ($add && $student instanceof Person)
            $student->addEnrolment($this, false);

        if (empty($student) && $this->student instanceof Person)
            $this->student->removeEnrolment($this);

        $this->student = $student;
        return $this;
    }

    /**
     * @var YearGroup|null
     */
    private $yearGroup;

    /**
     * @return YearGroup|null
     */
    public function getYearGroup(): ?YearGroup
    {
        return $this->yearGroup;
    }

    /**
     * @param YearGroup|null $yearGroup
     * @return StudentEnrolment
     */
    public function setYearGroup(?YearGroup $yearGroup): StudentEnrolment
    {
        $this->yearGroup = $yearGroup;
        return $this;
    }

    /**
     * @var RollGroup|null
     */
    private $rollGroup;

    /**
     * @return RollGroup|null
     */
    public function getRollGroup(): ?RollGroup
    {
        return $this->rollGroup;
    }

    /**
     * @param RollGroup|null $rollGroup
     * @return StudentEnrolment
     */
    public function setRollGroup(?RollGroup $rollGroup): StudentEnrolment
    {
        $this->rollGroup = $rollGroup;
        return $this;
    }
}