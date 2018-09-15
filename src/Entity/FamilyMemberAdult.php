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
 * Time: 07:59
 */
namespace App\Entity;

/**
 * Class FamilyMemberAdult
 * @package App\Entity
 */
class FamilyMemberAdult extends FamilyMember
{
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
     * @return FamilyMember
     */
    public function setChildDataAccess(?bool $childDataAccess): FamilyMember
    {
        $this->childDataAccess = $childDataAccess ? true : false ;
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
     * @return FamilyMember
     */
    public function setSequence(?int $sequence): FamilyMember
    {
        $this->sequence = $sequence;
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
     * @return FamilyMember
     */
    public function setContactCall(bool $contactCall): FamilyMember
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
     * @return FamilyMember
     */
    public function setContactSMS(bool $contactSMS): FamilyMember
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
     * @return FamilyMember
     */
    public function setContactEmail(bool $contactEmail): FamilyMember
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
     * @return FamilyMember
     */
    public function setContactMail(bool $contactMail): FamilyMember
    {
        $this->contactMail = $contactMail ? true : false ;
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
     * @return FamilyMember
     */
    public function setFamily(?Family $family, bool $add = true): FamilyMember
    {
        if ($add && $family instanceof Family)
            $family->addAdultMember($this, false);

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
     * @return FamilyMember
     */
    public function setPerson(?Person $person, bool $add = true): FamilyMember
    {
        if ($add && $person instanceof Person)
            $person->addAdultFamily($this, false);

        $this->person = $person;
        return $this;
    }

}