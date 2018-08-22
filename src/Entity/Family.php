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
 * Date: 22/08/2018
 * Time: 09:09
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Family
 * @package App\Entity
 */
class Family
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return self::$statusList;
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
     * @return Family
     */
    public function setId(?int $id): Family
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
     * @return Family
     */
    public function setName(?string $name): Family
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var Collection
     */
    private $members;

    /**
     * @return Collection
     */
    public function getMembers(): Collection
    {
        if (empty($this->members))
            $this->members = new ArrayCollection();

        if ($this->members instanceof PersistentCollection)
            $this->members->initialize();

        return $this->members;
    }

    /**
     * @param Collection|null $members
     * @return Family
     */
    public function setMembers(?Collection $members): Family
    {
        $this->members = $members;
        return $this;
    }

    /**
     * addMember
     *
     * @param FamilyPerson|null $person
     * @param bool $add
     * @return Family
     */
    public function addMember(?FamilyPerson $person, bool $add = true): Family
    {
        if (empty($person) || $this->getMembers()->contains($person))
            return $this;

        if ($add)
            $person->setFamily($this, false);

        $this->members->add($person);

        return $this;
    }

    /**
     * removeMember
     *
     * @param FamilyPerson|null $person
     * @return Family
     */
    public function removeMember(?FamilyPerson $person): Family
    {
        if (empty($person))
            return $this;

        $this->getMembers()->removeElement($person);

        $person->setFamily(null);

        return $this;
    }

    /**
     * @var string|null
     */
    private $nameAddress;

    /**
     * @return null|string
     */
    public function getNameAddress(): ?string
    {
        return $this->nameAddress;
    }

    /**
     * @param null|string $nameAddress
     * @return Family
     */
    public function setNameAddress(?string $nameAddress): Family
    {
        $this->nameAddress = $nameAddress;
        return $this;
    }

    /**
     * @var string|null
     */
    private $homeAddress;

    /**
     * @return null|string
     */
    public function getHomeAddress(): ?string
    {
        return $this->homeAddress;
    }

    /**
     * @param null|string $homeAddress
     * @return Family
     */
    public function setHomeAddress(?string $homeAddress): Family
    {
        $this->homeAddress = $homeAddress;
        return $this;
    }

    /**
     * @var string|null
     */
    private $homeAddressDistrict;

    /**
     * @return null|string
     */
    public function getHomeAddressDistrict(): ?string
    {
        return $this->homeAddressDistrict;
    }

    /**
     * @param null|string $homeAddressDistrict
     * @return Family
     */
    public function setHomeAddressDistrict(?string $homeAddressDistrict): Family
    {
        $this->homeAddressDistrict = $homeAddressDistrict;
        return $this;
    }

    /**
     * @var string|null
     */
    private $homeAddressCountry;

    /**
     * @return null|string
     */
    public function getHomeAddressCountry(): ?string
    {
        return $this->homeAddressCountry;
    }

    /**
     * @param null|string $homeAddressCountry
     * @return Family
     */
    public function setHomeAddressCountry(?string $homeAddressCountry): Family
    {
        $this->homeAddressCountry = $homeAddressCountry;
        return $this;
    }

    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private static $statusList = ['','married','separated','divorced','de_facto','other'];

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status = in_array($this->status, self::getStatusList()) ? $this->status : '';
    }

    /**
     * setStatus
     *
     * @param null|string $status
     * @return Family
     */
    public function setStatus(?string $status): Family
    {
        $this->status = in_array($status, self::getStatusList()) ? $status : '';
        return $this;
    }

    /**
     * @var string|null
     */
    private $languageHomePrimary;

    /**
     * @return null|string
     */
    public function getLanguageHomePrimary(): ?string
    {
        return $this->languageHomePrimary;
    }

    /**
     * @param null|string $languageHomePrimary
     * @return Family
     */
    public function setLanguageHomePrimary(?string $languageHomePrimary): Family
    {
        $this->languageHomePrimary = $languageHomePrimary;
        return $this;
    }

    /**
     * @var string|null
     */
    private $languageHomeSecondary;

    /**
     * @return null|string
     */
    public function getLanguageHomeSecondary(): ?string
    {
        return $this->languageHomeSecondary;
    }

    /**
     * @param null|string $languageHomeSecondary
     * @return Family
     */
    public function setLanguageHomeSecondary(?string $languageHomeSecondary): Family
    {
        $this->languageHomeSecondary = $languageHomeSecondary;
        return $this;
    }

    /**
     * @var string|null
     */
    private $familySync;

    /**
     * @return null|string
     */
    public function getFamilySync(): ?string
    {
        return $this->familySync;
    }

    /**
     * @param null|string $familySync
     * @return Family
     */
    public function setFamilySync(?string $familySync): Family
    {
        $this->familySync = $familySync;
        return $this;
    }
}