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
 * Time: 17:57
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Department
 * @package App\Entity
 */
class Department
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
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
     * @return Department
     */
    public function setId(?int $id): Department
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
     * @return Department
     */
    public function setName(?string $name): Department
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
     * @return Department
     */
    public function setNameShort(?string $nameShort): Department
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var array
     */
    public static $typeList = [
        'learning_area',
        'administration',
    ];

    /**
     * @return null|string
     */
    public function getType(): ?string
    {

        return $this->type = in_array($this->type, self::$typeList) ? $this->type : 'learning_area' ;
    }

    /**
     * @param null|string $type
     * @return Department
     */
    public function setType(?string $type): Department
    {
        $this->type = in_array($type, self::$typeList) ? $type : 'learning_area' ;;
        return $this;
    }

    /**
     * @var string|null
     */
    private $subjectListing;

    /**
     * @return null|string
     */
    public function getSubjectListing(): ?string
    {
        return $this->subjectListing;
    }

    /**
     * @param null|string $subjectListing
     * @return Department
     */
    public function setSubjectListing(?string $subjectListing): Department
    {
        $this->subjectListing = $subjectListing;
        return $this;
    }

    /**
     * @var string|null
     */
    private $blurb;

    /**
     * @return null|string
     */
    public function getBlurb(): ?string
    {
        return $this->blurb;
    }

    /**
     * @param null|string $blurb
     * @return Department
     */
    public function setBlurb(?string $blurb): Department
    {
        $this->blurb = $blurb;
        return $this;
    }

    /**
     * @var string|null
     */
    private $logo;

    /**
     * @return null|string
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param null|string $logo
     * @return Department
     */
    public function setLogo(?string $logo): Department
    {
        $this->logo = $logo;
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $members;

    /**
     * @var bool
     */
    private $membersSorted = false;

    /**
     * @return Collection
     */
    public function getMembers(): Collection
    {
        if (empty($this->members))
            $this->members = new ArrayCollection();

        if ($this->members instanceof PersistentCollection)
            $this->members->initialize();

        if (! $this->membersSorted) {
            $iterator = $this->members->getIterator();

            $iterator->uasort(
                function ($a, $b) {
                    return ($a->getMember()->getFullName(['preferredOnly' => true]) < $b->getMember()->getFullName(['preferredOnly' => true])) ? -1 : 1;
                }
            );

            $this->members = new ArrayCollection(iterator_to_array($iterator, false));
        }
        $this->membersSorted = true;

        return $this->members;
    }

    /**
     * @param Collection|null $members
     * @return Department
     */
    public function setMembers(?Collection $members): Department
    {
        $this->members = $members;
        return $this;
    }

    /**
     * addMember
     *
     * @param DepartmentStaff|null $member
     * @param bool $add
     * @return Department
     */
    public function addMember(?DepartmentStaff $member, $add = true): Department
    {
        if (empty($member) || $this->getMembers()->contains($member))
            return $this;

        if ($add)
            $member->setDepartment($this, false);

        $this->members->add($member);

        $this->membersSorted = false;

        return $this;
    }

    /**
     * removeMember
     *
     * @param DepartmentStaff|null $member
     * @return Department
     */
    public function removeMember(?DepartmentStaff $member): Department
    {
        $this->getMembers()->removeElement($member);

        if (! empty($member))
            $member->setDepartment(null, false);

        return $this;
    }

    /**
     * refresh
     *
     * @return Department
     */
    public function refresh(): Department
    {
//        $this->membersSorted = false;
        $this->getMembers();

        return $this;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?: 'Unknown Department';
    }
}