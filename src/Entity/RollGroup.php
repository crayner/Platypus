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
 * Date: 23/06/2018
 * Time: 07:42
 */
namespace App\Entity;

use App\Util\PersonNameHelper;
use App\Util\SchoolYearHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class RollGroup
 * @package App\Entity
 */
class RollGroup
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
     * @return RollGroup
     */
    public function setId(?int $id): RollGroup
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
     * @return RollGroup
     */
    public function setName(?string $name): RollGroup
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
     * @return RollGroup
     */
    public function setNameShort(?string $nameShort): RollGroup
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var boolean
     */
    private $attendance;

    /**
     * @return bool
     */
    public function isAttendance(): bool
    {
        return $this->attendance ? true : false ;
    }

    /**
     * @param bool $attendance
     * @return RollGroup
     */
    public function setAttendance(bool $attendance): RollGroup
    {
        $this->attendance = $attendance ? true : false ;
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
    public function setWebsite(?string $website): RollGroup
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @var SchoolYear
     */
    private $schoolYear;

    /**
     * @return SchoolYear|null
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * @param SchoolYear|null $schoolYear
     * @return RollGroup
     */
    public function setSchoolYear(?SchoolYear $schoolYear): RollGroup
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * @var RollGroup
     */
    private $nextRollGroup;

    /**
     * @return RollGroup|null
     */
    public function getNextRollGroup(): ?RollGroup
    {
        return $this->nextRollGroup;
    }

    /**
     * @param RollGroup|null $nextRollGroup
     * @return RollGroup
     */
    public function setNextRollGroup(?RollGroup $nextRollGroup): RollGroup
    {
        $this->nextRollGroup = $nextRollGroup;
        return $this;
    }

    /**
     * @var Collection
     */
    private $tutors;

    /**
     * @return Collection
     */
    public function getTutors(): Collection
    {
        $this->tutors = $this->tutors ?: new ArrayCollection();

        if ($this->tutors instanceof PersistentCollection)
            $this->tutors->initialize();

        return $this->tutors;
    }

    /**
     * @param Collection|null $tutors
     * @return RollGroup
     */
    public function setTutors(?Collection $tutors): RollGroup
    {
        $this->tutors = $tutors;
        return $this;
    }

    /**
     * addTutor
     *
     * @param Person|null $tutor
     * @param bool $add
     * @return RollGroup
     */
    public function addTutor(?Person $tutor, $add = true): RollGroup
    {
        if (empty($tutor) || $this->getTutors()->contains($tutor) || $this->getTutors()->count() >= 3)
            return $this;

        $this->tutors->add($tutor);

        return $this;
    }

    /**
     * removeTutor
     *
     * @param Person|null $tutor
     * @return RollGroup
     */
    public function removeTutor(?Person $tutor): RollGroup
    {
        $this->getTutors()->removeElement($tutor);
        return $this;
    }

    /**
     * @var Collection
     */
    private $assistants;

    /**
     * @return Collection
     */
    public function getAssistants(): Collection
    {
        $this->assistants = $this->assistants ?: new ArrayCollection();

        if ($this->assistants instanceof PersistentCollection)
            $this->assistants->initialize();

        return $this->assistants;
    }

    /**
     * @param Collection|null $assistants
     * @return RollGroup
     */
    public function setAssistants(?Collection $assistants): RollGroup
    {
        $this->assistants = $assistants;
        return $this;
    }

    /**
     * addAssistant
     *
     * @param Person|null $assistant
     * @param bool $add
     * @return RollGroup
     */
    public function addAssistant(?Person $assistant, $add = true): RollGroup
    {
        if (empty($assistant) || $this->getAssistants()->contains($assistant) || $this->getAssistants()->count() >= 3)
            return $this;

        $this->assistants->add($assistant);

        return $this;
    }

    /**
     * removeAssistant
     *
     * @param Person|null $assistant
     * @return RollGroup
     */
    public function removeAssistant(?Person $assistant): RollGroup
    {
        $this->getAssistants()->removeElement($assistant);
        return $this;
    }

    /**
     * @var Facility|null
     */
    private $facility;

    /**
     * @return Facility|null
     */
    public function getFacility(): ?Facility
    {
        return $this->facility;
    }

    /**
     * @param Facility|null $facility
     * @return RollGroup
     */
    public function setFacility(?Facility $facility): RollGroup
    {
        $this->facility = $facility;
        return $this;
    }

    /**
     * RollGroup constructor.
     */
    public function __construct()
    {
        $this->setSchoolYear(SchoolYearHelper::getCurrentSchoolYear());
    }

    /**
     * getTutorList
     *
     * @return string
     */
    public function getTutorList(): string
    {
        $tutors = '';
        foreach($this->getTutors()->toArray() as $tutor)
            $tutors .= PersonNameHelper::getFullName($tutor) . "<br />\n";

        return rtrim($tutors, "<br />\n");
    }

    /**
     * canDelete
     *
     * @todo Can Delete test
     * @return bool
     */
    public function canDelete(): bool
    {
        return true;
    }
}