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
 * Date: 15/09/2018
 * Time: 11:49
 */
namespace App\Entity;

/**
 * Class FamilyRelationship
 * @package App\Entity
 */
class FamilyRelationship
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getRelationshipList(): array
    {
        return self::$relationshipList;
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
     * @return FamilyRelationship
     */
    public function setId(?int $id): FamilyRelationship
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var array
     */
    private static $relationshipList = [
        'mother',
        'father' ,
        'step_mother',
        'step_father',
        'adoptive_parent',
        'guardian',
        'sibling',
        'grandmother',
        'grandfather',
        'aunt',
        'uncle',
        'nanny_helper',
        'family_friend',
        'other',

    ];

    /**
     * @var string|null
     */
    private $relationship;

    /**
     * @return null|string
     */
    public function getRelationship(): ?string
    {
        return $this->relationship;
    }

    /**
     * @param null|string $relationship
     * @return FamilyRelationship
     */
    public function setRelationship(?string $relationship): FamilyRelationship
    {
        $this->relationship = $relationship;
        return $this;
    }

    /**
     * @var Family|null
     */
    private $family;

    /**
     * @return Family|null
     */
    public function getFamily(): ?Family
    {
        return $this->family;
    }

    /**
     * setFamily
     *
     * @param Family|null $family
     * @param bool $add
     * @return FamilyRelationship
     */
    public function setFamily(?Family $family, bool $add = true): FamilyRelationship
    {
        if (empty($family) && ! empty($this->family))
            $this->family->removeRelationship($this);

        $this->family = $family;
        if ($add || ! empty($family))
            $family->addRelationship($this, false);

        return $this;
    }

    /**
     * @var Person|null
     */
    private $adult;

    /**
     * @return Person|null
     */
    public function getAdult(): ?Person
    {
        return $this->adult;
    }

    /**
     * @param Person|null $adult
     * @return FamilyRelationship
     */
    public function setAdult(?Person $adult): FamilyRelationship
    {
        $this->adult = $adult;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $child;

    /**
     * @return Person|null
     */
    public function getChild(): ?Person
    {
        return $this->child;
    }

    /**
     * @param Person|null $child
     * @return FamilyRelationship
     */
    public function setChild(?Person $child): FamilyRelationship
    {
        $this->child = $child;
        return $this;
    }
}