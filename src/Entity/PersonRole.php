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
 * Time: 06:07
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class PersonRole
 * @package App\Entity
 */
class PersonRole
{
    /**
     * PersonRole constructor.
     */
    public function __construct()
    {
        $this->setFutureYearsLogin(true);
        $this->setPastYearsLogin(true);
        $this->setType('additional');
    }

    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getRestrictionList(): array
    {
        return self::$restrictionList;
    }

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * @return array
     */
    public static function getCategoryList(): array
    {
        return self::$categoryList;
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
     * @return PersonRole
     */
    public function setId(?int $id): PersonRole
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string
     */
    private $category;

    /**
     * @var array
     */
    private static $categoryList = ['staff', 'student', 'parent', 'other'];

    /**
     * @return string
     */
    public function getCategory(): string
    {
        $this->category = strtolower($this->category);
        return in_array($this->category, self::getCategoryList()) ? $this->category : 'staff';
    }

    /**
     * @param null|string $category
     * @return PersonRole
     */
    public function setCategory(?string $category): PersonRole
    {
        $category = strtolower($category);
        $this->category = in_array($category, self::getCategoryList()) ? $category : 'staff';
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
     * @return PersonRole
     */
    public function setName(?string $name): PersonRole
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
     * @return PersonRole
     */
    public function setNameShort(?string $nameShort): PersonRole
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
     * @return PersonRole
     */
    public function setDescription(?string $description): PersonRole
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = ['core', 'additional'];

    /**
     * @return string
     */
    public function getType(): string
    {
        return in_array($this->type, self::getTypeList()) ? $this->type : 'core';
    }

    /**
     * @param string|null $type
     * @return PersonRole
     */
    public function setType(?string $type): PersonRole
    {
        $this->type = in_array($type, self::getTypeList()) ? $type : 'core';
        return $this;
    }

    /**
     * @var bool
     */
    private $futureYearsLogin = true;

    /**
     * @return bool
     */
    public function isFutureYearsLogin(): bool
    {
        return $this->futureYearsLogin ? true : false ;
    }

    /**
     * @param bool $futureYearsLogin
     * @return PersonRole
     */
    public function setFutureYearsLogin(?bool $futureYearsLogin): PersonRole
    {
        $this->futureYearsLogin = $futureYearsLogin ? true : false ;
        return $this;
    }

    /**
     * @var bool
     */
    private $pastYearsLogin = true;

    /**
     * @return bool
     */
    public function isPastYearsLogin(): bool
    {
        return $this->pastYearsLogin ? true : false ;
    }

    /**
     * setPastYearsLogin
     *
     * @param bool|null $pastYearsLogin
     * @return PersonRole
     */
    public function setPastYearsLogin(?bool $pastYearsLogin): PersonRole
    {
        $this->pastYearsLogin = $pastYearsLogin ? true : false ;
        return $this;
    }

    /**
     * @var string
     */
    private $restriction = 'none';

    /**
     * @var array
     */
    private static $restrictionList = ['none', 'same_role', 'admin_only'];

    /**
     * @return string
     */
    public function getRestriction(): string
    {
        return in_array($this->restriction, self::getRestrictionList()) ? $this->restriction : 'none';
    }

    /**
     * setRestriction
     *
     * @param null|string $restriction
     * @return PersonRole
     */
    public function setRestriction(?string $restriction): PersonRole
    {
        $this->restriction = in_array($restriction, self::getRestrictionList()) ? $restriction : 'none';
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $people;

    /**
     * @return Collection|null
     */
    public function getPeople(): ?Collection
    {
        if (empty($this->people))
            $this->people = new ArrayCollection();

        if ($this->people instanceof PersistentCollection)
            $this->people->initialize();

        return $this->people;
    }

    /**
     * @param Collection|null $people
     * @return PersonRole
     */
    public function setPeople(?Collection $people): PersonRole
    {
        $this->people = $people;
        return $this;
    }

    /**
     * addPerson
     *
     * @param Person|null $person
     * @param bool $add
     * @return PersonRole
     */
    public function addPerson(?Person $person, bool $add = true): PersonRole
    {
        if (empty($person) || $this->getPeople()->contains($person))
            return $this;

        if ($add)
            $person->setPrimaryRole($this, false);

        $this->people->add($person);

        return $this;
    }

    /**
     * removePerson
     *
     * @param Person|null $person
     * @return PersonRole
     */
    public function removePerson(?Person $person): PersonRole
    {
        if (empty($person))
            return $this;

        $this->getPeople()->removeElement($person);
        $person->setPrimaryRole(null);

        return $this;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCategory();
    }
}