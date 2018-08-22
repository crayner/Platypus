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
 * Time: 08:42
 */
namespace App\Entity;

/**
 * Class FamilyPerson
 * @package App\Entity
 */
class FamilyPerson
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
     * @return FamilyPerson
     */
    public function setId(?int $id): FamilyPerson
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = ['adult','child'];

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type = $this->type === 'child' ? 'child' : 'adult';
    }

    /**
     * @param string|null $type
     * @return FamilyPerson
     */
    public function setType(?string $type): FamilyPerson
    {
        $this->type = $type === 'child' ? 'child' : 'adult';
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
     * @return FamilyPerson
     */
    public function setFamily(?Family $family, bool $add = true): FamilyPerson
    {
        if ($add && $family instanceof Family)
            $family->addMember($this, false);

        $this->family = $family;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $person;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * setPerson
     *
     * @param Person|null $person
     * @param bool $add
     * @return FamilyPerson
     */
    public function setPerson(?Person $person, bool $add = true): FamilyPerson
    {
        if ($add && $person instanceof Person)
            $person->addFamily($this, false);

        $this->person = $person;
        return $this;
    }

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return FamilyPerson
     */
    public function setComment(?string $comment): FamilyPerson
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @var bool
     */
    private $childDataAccess;

    /**
     * @return bool
     */
    public function isChildDataAccess(): bool
    {
        return $this->childDataAccess ? true : false ;
    }

    /**
     * setChildDataAccess
     *
     * @param bool|null $childDataAccess
     * @return FamilyPerson
     */
    public function setChildDataAccess(?bool $childDataAccess): FamilyPerson
    {
        $this->childDataAccess = $childDataAccess ? true : false ;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $contactPriority;

    /**
     * @return int|null
     */
    public function getContactPriority(): ?int
    {
        return $this->contactPriority;
    }

    /**
     * @param int|null $contactPriority
     * @return FamilyPerson
     */
    public function setContactPriority(?int $contactPriority): FamilyPerson
    {
        $this->contactPriority = $contactPriority;
        return $this;
    }

    /**
     * @var boolean
     */
    private $contactCall;

    /**
     * @return bool
     */
    public function isContactCall(): bool
    {
        return $this->contactCall ? true : false ;
    }

    /**
     * @param bool $contactCall
     * @return FamilyPerson
     */
    public function setContactCall(bool $contactCall): FamilyPerson
    {
        $this->contactCall = $contactCall ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $contactSMS;

    /**
     * @return bool
     */
    public function isContactSMS(): bool
    {
        return $this->contactSMS ? true : false ;
    }

    /**
     * @param bool $contactSMS
     * @return FamilyPerson
     */
    public function setContactSMS(bool $contactSMS): FamilyPerson
    {
        $this->contactSMS = $contactSMS ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $contactEmail;

    /**
     * @return bool
     */
    public function isContactEmail(): bool
    {
        return $this->contactEmail ? true : false ;
    }

    /**
     * @param bool $contactEmail
     * @return FamilyPerson
     */
    public function setContactEmail(bool $contactEmail): FamilyPerson
    {
        $this->contactEmail = $contactEmail ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $contactMail;

    /**
     * @return bool
     */
    public function isContactMail(): bool
    {
        return $this->contactMail ? true : false ;
    }

    /**
     * @param bool $contactMail
     * @return FamilyPerson
     */
    public function setContactMail(bool $contactMail): FamilyPerson
    {
        $this->contactMail = $contactMail ? true : false ;
        return $this;
    }
}