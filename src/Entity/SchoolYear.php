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

use App\Entity\Extension\SchoolYearExtension;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class SchoolYear
 * @package App\Entity
 */
class SchoolYear extends SchoolYearExtension
{
    /**
     * SchoolYear constructor.
     */
    public function __construct()
    {
        $this->setStatus('upcoming');
    }

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
     * @return SchoolYear
     */
    public function setId(?int $id): SchoolYear
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
     * @return SchoolYear
     */
    public function setName(?string $name): SchoolYear
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var array
     */
    public static $statusList = [
        'past',
        'current',
        'upcoming',
    ];

    /**
     * @return array
     */
    public function getStatusList(): array
    {
        return self::$statusList;
    }

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status = in_array($this->status, self::$statusList) ? $this->status : 'upcoming';
    }

    /**
     * @param null|string $status
     * @return SchoolYear
     */
    public function setStatus(?string $status): SchoolYear
    {
        $this->status = in_array($status, self::$statusList) ? $status : 'upcoming';
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequenceNumber;

    /**
     * @return int|null
     */
    public function getSequenceNumber(): ?int
    {
        return $this->sequenceNumber;
    }

    /**
     * @param int|null $sequenceNumber
     * @return SchoolYear
     */
    public function setSequenceNumber(?int $sequenceNumber): SchoolYear
    {
        $this->sequenceNumber = $sequenceNumber ?: 0;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $firstDay;

    /**
     * @return \DateTime
     */
    public function getFirstDay(): \DateTime
    {
        return $this->firstDay;
    }

    /**
     * @param \DateTime $firstDay
     * @return SchoolYear
     */
    public function setFirstDay(\DateTime $firstDay): SchoolYear
    {
        $this->firstDay = $firstDay;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $lastDay;

    /**
     * @return \DateTime
     */
    public function getLastDay(): \DateTime
    {
        return $this->lastDay;
    }

    /**
     * @param \DateTime $lastDay
     * @return SchoolYear
     */
    public function setLastDay(\DateTime $lastDay): SchoolYear
    {
        $this->lastDay = $lastDay;
        return $this;
    }

    /**
     * @var Collection
     */
    private $terms;

    /**
     * @return Collection
     */
    public function getTerms(): Collection
    {
        if (empty($this->terms))
            $this->terms = new ArrayCollection();

        if ($this->terms instanceof PersistentCollection)
            $this->terms->initialize();

        return $this->terms;
    }

    /**
     * @param Collection $terms
     * @return SchoolYear
     */
    public function setTerms(?Collection $terms): SchoolYear
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * addTerm
     *
     * @param SchoolYearTerm|null $term
     * @param bool $add
     * @return SchoolYear
     */
    public function addTerm(?SchoolYearTerm $term, $add = true): SchoolYear
    {
        if ($term || $this->getTerms()->contains($term))
            return $this;

        if ($add)
            $term->setSchoolYear($this, false);

        $this->terms->add($term);

        return $this;
    }

    /**
     * removeTerm
     *
     * @param SchoolYearTerm|null $term
     * @return SchoolYear
     */
    public function removeTerm(?SchoolYearTerm $term): SchoolYear
    {
        $this->getTerms()->removeElement($term);

        return $this;
    }

    /**
     * @var Collection
     */
    private $specialDays;

    /**
     * @return Collection
     */
    public function getSpecialDays(): Collection
    {
        if (empty($this->specialDays))
            $this->specialDays = new ArrayCollection();

        if ($this->specialDays instanceof PersistentCollection)
            $this->specialDays->initialize();

        return $this->specialDays;
    }

    /**
     * @param Collection $specialDays
     * @return SchoolYear
     */
    public function setSpecialDays(?Collection $specialDays): SchoolYear
    {
        $this->specialDays = $specialDays;
        return $this;
    }

    /**
     * addSpecialDay
     *
     * @param SchoolYearSpecialDay|null $specialDay
     * @param bool $add
     * @return SchoolYear
     */
    public function addSpecialDay(?SchoolYearSpecialDay $specialDay, $add = true): SchoolYear
    {
        if ($specialDay || $this->getSpecialDays()->contains($specialDay))
            return $this;

        if ($add)
            $specialDay->setSchoolYear($this, false);

        $this->specialDays->add($specialDay);

        return $this;
    }

    /**
     * removeSpecialDay
     *
     * @param SchoolYearSpecialDay|null $specialDay
     * @return SchoolYear
     */
    public function removeSpecialDay(?SchoolYearSpecialDay $specialDay): SchoolYear
    {
        $this->getSpecialDays()->removeElement($specialDay);

        return $this;
    }
}