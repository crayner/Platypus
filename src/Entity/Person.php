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
 * Time: 16:39
 */
namespace App\Entity;

use App\Util\PersonNameHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Hillrange\Form\Util\FileBlob;
use Hillrange\Security\Util\ParameterInjector;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Person
 * @package App\Entity
 */
class Person
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getTitleList(): array
    {
        return ParameterInjector::getParameter('personal.title.list');
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return self::$statusList;
    }

    /**
     * @return array
     */
    public static function getGenderList(): array
    {
        return self::$genderList;
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
     * @return Person
     */
    public function setId(?int $id): Person
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var UserInterface|null
     */
    private $user;

    /**
     * @return null|UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param null|UserInterface $user
     * @return Person
     */
    public function setUser(?UserInterface $user): Person
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @return null|string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param null|string $surname
     * @return Person
     */
    public function setSurname(?string $surname): Person
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     * @return Person
     */
    public function setFirstName(?string $firstName): Person
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @var string|null
     */
    private $preferredName;

    /**
     * @return null|string
     */
    public function getPreferredName(): ?string
    {
        return $this->preferredName;
    }

    /**
     * @param null|string $preferredName
     * @return Person
     */
    public function setPreferredName(?string $preferredName): Person
    {
        $this->preferredName = $preferredName;
        return $this;
    }

    /**
     * @var string|null
     */
    private $officialName;

    /**
     * @return null|string
     */
    public function getOfficialName(): ?string
    {
        return $this->officialName;
    }

    /**
     * @param null|string $officialName
     * @return Person
     */
    public function setOfficialName(?string $officialName): Person
    {
        $this->officialName = $officialName;
        return $this;
    }

    /**
     * @var string|null
     */
    private $title;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title = in_array($this->title, self::getStatusList()) ? $this->title : '';
    }

    /**
     * @param null|string $title
     * @return Person
     */
    public function setTitle(?string $title): Person
    {
        $this->title = in_array($title, self::getStatusList()) ? $title : '';
        return $this;
    }

    /**
     * @var string
     */
    private $gender;

    /**
     * @var array
     */
    private static $genderList = [
        'm',
        'f',
        'o',
        'u',
    ];

    /**
     * @return string
     */
    public function getGender(): ?string
    {
        return in_array($this->gender, self::getGenderList()) ? $this->gender : 'u';
    }

    /**
     * @param null|string $gender
     * @return Person
     */
    public function setGender(?string $gender): Person
    {
        $this->gender = in_array($gender, self::getGenderList()) ? $gender : 'u';
        return $this;
    }

    /**
     * @var string|null
     */
    private $email;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Person
     */
    public function setEmail(?string $email): Person
    {
        $this->email = $email;
        return $this;
    }

    /**
     * getFullName
     *
     * @param array $options
     * @return string
     */
    public function getFullName(array $options = []): string
    {
        return PersonNameHelper::getFullName($this, $options);
    }

    /**
     * @var Collection|null
     */
    private $departments;

    /**
     * @return Collection
     */
    public function getDepartments(): Collection
    {
        if (empty($this->departments))
            $this->departments = new ArrayCollection();

        if ($this->departments instanceof PersistentCollection)
            $this->departments->initialize();

        return $this->departments;
    }

    /**
     * @param Collection|null $departments
     * @return Department
     */
    public function setDepartments(?Collection $departments): Person
    {
        $this->departments = $departments;
        return $this;
    }

    /**
     * addDepartment
     *
     * @param DepartmentStaff|null $department
     * @param bool $add
     * @return Department
     */
    public function addDepartment(?DepartmentStaff $department, $add = true): Person
    {
        if (empty($department) || $this->getDepartments()->contains($department))
            return $this;

        if ($add)
            $department->setMember($this, false);

        $this->departments->add($department);

        return $this;
    }

    /**
     * removeDepartment
     *
     * @param DepartmentStaff|null $department
     * @return Department
     */
    public function removeDepartment(?DepartmentStaff $department): Person
    {
        $this->getDepartments()->removeElement($department);

        if (!empty($department))
            $department->setMember(null, false);

        return $this;
    }

    /**
     * @var bool
     */
    private $receiveNotificationEmails;

    /**
     * @return bool
     */
    public function isReceiveNotificationEmails(): bool
    {
        return $this->receiveNotificationEmails ? true : false;
    }

    /**
     * @param bool $receiveNotificationEmails
     * @return Person
     */
    public function setReceiveNotificationEmails(bool $receiveNotificationEmails): Person
    {
        $this->receiveNotificationEmails = $receiveNotificationEmails ? true : false;
        return $this;
    }

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->receiveNotificationEmails = true;
        $this->gender = 'u';
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId() . ': ' . PersonNameHelper::getFullName($this);
    }

    /**
     * @var null|Staff
     */
    private $staff;

    /**
     * @return Staff|null
     */
    public function getStaff(): ?Staff
    {
        return $this->staff;
    }

    /**
     * @param Staff|null $staff
     * @return Person
     */
    public function setStaff(?Staff $staff): Person
    {
        $this->staff = $staff;
        return $this;
    }

    /**
     * @var string|null
     */
    private $personalCalendarFeed;

    /**
     * @return null|string
     */
    public function getPersonalCalendarFeed(): ?string
    {
        return $this->personalCalendarFeed;
    }

    /**
     * @param null|string $personalCalendarFeed
     * @return Person
     */
    public function setPersonalCalendarFeed(?string $personalCalendarFeed): Person
    {
        $this->personalCalendarFeed = $personalCalendarFeed;
        return $this;
    }

    /**
     * @var string|null
     */
    private $personalBackground;

    /**
     * @return null|string
     */
    public function getPersonalBackground(): ?string
    {
        return $this->personalBackground;
    }

    /**
     * @param null|string $personalBackground
     * @return Person
     */
    public function setPersonalBackground(?string $personalBackground): Person
    {
        $this->personalBackground = $personalBackground;
        return $this;
    }

    /**
     * @var string|null
     */
    private $personalTheme;

    /**
     * @return null|string
     */
    public function getPersonalTheme(): ?string
    {
        return $this->personalTheme;
    }

    /**
     * @param null|string $personalTheme
     * @return Person
     */
    public function setPersonalTheme(?string $personalTheme): Person
    {
        $this->personalTheme = $personalTheme;
        return $this;
    }

    /**
     * @var string|null
     */
    private $personalLanguage;

    /**
     * @return null|string
     */
    public function getPersonalLanguage(): ?string
    {
        return $this->personalLanguage;
    }

    /**
     * @param null|string $personalLanguage
     * @return Person
     */
    public function setPersonalLanguage(?string $personalLanguage): Person
    {
        $this->personalLanguage = $personalLanguage;
        return $this;
    }

    /**
     * @var string|null
     */
    private $photo;

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param null|string $photo
     * @return Person
     */
    public function setPhoto(?string $photo): Person
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private static $statusList = [
        'full', 'expected', 'left', 'pending'
    ];

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status = in_array($this->status, self::getStatusList()) ? $this->status : 'full';
    }

    /**
     * @param string|null $status
     * @return Person
     */
    public function setStatus(?string $status): Person
    {
        $this->status = in_array($status, self::getStatusList()) ? $status : 'full';
        return $this;
    }

    /**
     * @var PersonRole|null
     */
    private $primaryRole;

    /**
     * @return PersonRole|null
     */
    public function getPrimaryRole(): ?PersonRole
    {
        return $this->primaryRole;
    }

    /**
     * @param PersonRole|null $primaryRole
     * @param bool $add
     * @return Person
     */
    public function setPrimaryRole(?PersonRole $primaryRole, bool $add = true): Person
    {
        if ($primaryRole instanceof PersonRole && $add)
            $primaryRole->addPerson($this, false);

        $this->primaryRole = $primaryRole;

        return $this;
    }

    /**
     * @var Collection
     */
    private $adultFamilies;

    /**
     * @return Collection
     */
    public function getAdultFamilies(): Collection
    {
        if (empty($this->adultFamilies))
            $this->adultFamiles = new ArrayCollection();

        if ($this->adultFamilies instanceof PersistentCollection)
            $this->adultFamilies->initialize();

        return $this->adultFamilies;
    }

    /**
     * @param Collection $adultFamilies
     * @return Person
     */
    public function setAdultFamilies(Collection $adultFamilies): Person
    {
        $this->adultFamilies = $adultFamilies;
        return $this;
    }

    /**
     * addAdultFamily
     *
     * @param FamilyMemberAdult|null $adultFamily
     * @param bool $add
     * @return Person
     */
    public function addAdultFamily(?FamilyMemberAdult $adultFamily, bool $add = true): Person
    {
        if (empty($adultFamily) || $this->getAdultFamilies()->contains($adultFamily))
            return $this;

        if ($add)
            $adultFamily->setPerson($this, false);

        $this->adultFamilies->add($adultFamily);

        return $this;
    }

    /**
     * removeAdultFamily
     *
     * @param FamilyMemberAdult|null $adultFamily
     * @return Person
     */
    public function removeAdultFamily(?FamilyMemberAdult $adultFamily): Person
    {
        if (empty($adultFamily))
            return $this;

        $adultFamily->setPerson(null);
        $this->getAdultFamilies()->removeElement($adultFamily);

        return $this;
    }

    /**
     * @var Collection
     */
    private $childFamilies;

    /**
     * @return Collection
     */
    public function getChildFamilies(): Collection
    {
        if (empty($this->childFamilies))
            $this->childFamiles = new ArrayCollection();

        if ($this->childFamilies instanceof PersistentCollection)
            $this->childFamilies->initialize();

        return $this->childFamilies;
    }

    /**
     * @param Collection $childFamilies
     * @return Person
     */
    public function setChildFamilies(Collection $childFamilies): Person
    {
        $this->childFamilies = $childFamilies;
        return $this;
    }

    /**
     * addChildFamily
     *
     * @param FamilyMemberChild|null $childFamily
     * @param bool $add
     * @return Person
     */
    public function addChildFamily(?FamilyMemberChild $childFamily, bool $add = true): Person
    {
        if (empty($childFamily) || $this->getChildFamilies()->contains($childFamily))
            return $this;

        if ($add)
            $childFamily->setPerson($this, false);

        $this->childFamilies->add($childFamily);

        return $this;
    }

    /**
     * removeChildFamily
     *
     * @param FamilyMemberChild|null $childFamily
     * @return Person
     */
    public function removeChildFamily(?FamilyMemberChild $childFamily): Person
    {
        if (empty($childFamily))
            return $this;

        $childFamily->setPerson(null);
        $this->getChildFamilies()->removeElement($childFamily);

        return $this;
    }

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return false;
    }

    /**
     * @var string|null
     */
    private $nameInCharacters;

    /**
     * @return null|string
     */
    public function getNameInCharacters(): ?string
    {
        return $this->nameInCharacters;
    }

    /**
     * @param null|string $nameInCharacters
     * @return Person
     */
    public function setNameInCharacters(?string $nameInCharacters): Person
    {
        $this->nameInCharacters = $nameInCharacters;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $dob;

    /**
     * @return \DateTime|null
     */
    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    /**
     * @param \DateTime|null $dob
     * @return Person
     */
    public function setDob(?\DateTime $dob): Person
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @var Collection
     */
    private $secondaryRoles;

    /**
     * @return Collection
     */
    public function getSecondaryRoles(): Collection
    {
        if (empty($this->secondaryRoles))
            $this->secondaryRoles = new ArrayCollection();

        if ($this->secondaryRoles instanceof PersistentCollection)
            $this->secondaryRoles->initialize();

        return $this->secondaryRoles;
    }

    /**
     * @param Collection $secondaryRoles
     * @return Person
     */
    public function setSecondaryRoles($secondaryRoles): Person
    {
        $this->secondaryRoles = is_array($secondaryRoles) ? new ArrayCollection($secondaryRoles) : $secondaryRoles ;

        return $this;
    }

    /**
     * @var string|null
     */
    private $emailAlternate;

    /**
     * @return null|string
     */
    public function getEmailAlternate(): ?string
    {
        return $this->emailAlternate;
    }

    /**
     * @param null|string $emailAlternate
     * @return Person
     */
    public function setEmailAlternate(?string $emailAlternate): Person
    {
        $this->emailAlternate = $emailAlternate;
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $addresses;

    /**
     * @return Collection
     */
    public function getAddresses(): Collection
    {
        if (empty($this->addresses))
            $this->addresses = new ArrayCollection();

        if ($this->addresses instanceof PersistentCollection)
            $this->addresses->initialize();

        return $this->addresses;
    }

    /**
     * @param Collection|null $addresses
     * @return Person
     */
    public function setAddresses(?Collection $addresses): Person
    {
        $this->addresses = $addresses;
        return $this;
    }

    /**
     * addAddress
     *
     * @param Address|null $address
     * @return Person
     */
    public function addAddress(?Address $address): Person
    {
        if (empty($address) || $this->getAddresses()->contains($address))
            return $this;

        $this->addresses->add($address);

        return $this;
    }

    /**
     * removeAddress
     *
     * @param Address|null $address
     * @return Person
     */
    public function removeAddress(?Address $address): Person
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * @var Collection|null
     */
    private $phones;

    /**
     * getPhones
     *
     * @return Collection
     */
    public function getPhones(): Collection
    {
        if (empty($this->phones))
            $this->phones = new ArrayCollection();

        if ($this->phones instanceof PersistentCollection)
            $this->phones->initialize();

        return $this->phones;
    }

    /**
     * setPhones
     *
     * @param Collection|null $phones
     * @return Person
     */
    public function setPhones(?Collection $phones): Person
    {
        $this->phones = $phones;
        return $this;
    }

    /**
     * addPhone
     *
     * @param Phone|null $phone
     * @return Person
     */
    public function addPhone(?Phone $phone): Person
    {
        if (empty($phone) || $this->getPhones()->contains($phone))
            return $this;

        $this->phones->add($phone);

        return $this;
    }

    /**
     * removePhone
     *
     * @param Phone|null $phone
     * @return Person
     */
    public function removePhone(?Phone $phone): Person
    {
        $this->phones->removeElement($phone);

        return $this;
    }

    /**
     * @var string|null
     */
    private $lastSchool;

    /**
     * getLastSchool
     *
     * @return null|string
     */
    public function getLastSchool(): ?string
    {
        return $this->lastSchool;
    }

    /**
     * setLastSchool
     *
     * @param null|string $lastSchool
     * @return Person
     */
    public function setLastSchool(?string $lastSchool): Person
    {
        $this->lastSchool = $lastSchool;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $dateStart;

    /**
     * @return \DateTime|null
     */
    public function getDateStart(): ?\DateTime
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime|null $dateStart
     * @return Person
     */
    public function setDateStart(?\DateTime $dateStart): Person
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $dateEnd;

    /**
     * @return \DateTime|null
     */
    public function getDateEnd(): ?\DateTime
    {
        return $this->dateEnd;
    }

    /**
     * @param \DateTime|null $dateEnd
     * @return Person
     */
    public function setDateEnd(?\DateTime $dateEnd): Person
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    /**
     * @var SchoolYear|null
     */
    private $graduationYear;

    /**
     * @return SchoolYear|null
     */
    public function getGraduationYear(): ?SchoolYear
    {
        return $this->graduationYear;
    }

    /**
     * @param SchoolYear|null $graduationYear
     * @return Person
     */
    public function setGraduationYear(?SchoolYear $graduationYear): Person
    {
        $this->graduationYear = $graduationYear;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nextSchool;

    /**
     * getNextSchool
     *
     * @return null|string
     */
    public function getNextSchool(): ?string
    {
        return $this->nextSchool;
    }

    /**
     * setNextSchool
     *
     * @param null|string $nextSchool
     * @return Person
     */
    public function setNextSchool(?string $nextSchool): Person
    {
        $this->nextSchool = $nextSchool;
        return $this;
    }

    /**
     * @var string|null
     */
    private $departureReason;

    /**
     * getDepartureReason
     *
     * @return null|string
     */
    public function getDepartureReason(): ?string
    {
        return $this->departureReason;
    }

    /**
     * setDepartureReason
     *
     * @param null|string $departureReason
     * @return Person
     */
    public function setDepartureReason(?string $departureReason): Person
    {
        $this->departureReason = $departureReason;
        return $this;
    }

    /**
     * @var string|null
     */
    private $languageFirst;

    /**
     * getLanguageFirst
     *
     * @return null|string
     */
    public function getLanguageFirst(): ?string
    {
        return $this->languageFirst;
    }

    /**
     * setLanguageFirst
     *
     * @param null|string $languageFirst
     * @return Person
     */
    public function setLanguageFirst(?string $languageFirst): Person
    {
        $this->languageFirst = $languageFirst;
        return $this;
    }

    /**
     * @var string|null
     */
    private $languageSecond;

    /**
     * getLanguageSecond
     *
     * @return null|string
     */
    public function getLanguageSecond(): ?string
    {
        return $this->languageSecond;
    }

    /**
     * setLanguageSecond
     *
     * @param null|string $languageSecond
     * @return Person
     */
    public function setLanguageSecond(?string $languageSecond): Person
    {
        $this->languageSecond = $languageSecond;
        return $this;
    }

    /**
     * @var string|null
     */
    private $languageThird;

    /**
     * getLanguageThird
     *
     * @return null|string
     */
    public function getLanguageThird(): ?string
    {
        return $this->languageThird;
    }

    /**
     * setLanguageThird
     *
     * @param null|string $languageThird
     * @return Person
     */
    public function setLanguageThird(?string $languageThird): Person
    {
        $this->languageThird = $languageThird;
        return $this;
    }

    /**
     * @var string|null
     */
    private $countryOfBirth;

    /**
     * getCountryOfBirth
     *
     * @return null|string
     */
    public function getCountryOfBirth(): ?string
    {
        return $this->countryOfBirth;
    }

    /**
     * setCountryOfBirth
     *
     * @param null|string $countryOfBirth
     * @return Person
     */
    public function setCountryOfBirth(?string $countryOfBirth): Person
    {
        $this->countryOfBirth = $countryOfBirth;
        return $this;
    }

    /**
     * @var string|null
     */
    private $birthCertificateScan;

    /**
     * getBirthCertificateScan
     *
     * @return null|string
     */
    public function getBirthCertificateScan(): ?string
    {
        return $this->birthCertificateScan;
    }

    /**
     * setBirthCertificateScan
     *
     * @param null|string $birthCertificateScan
     * @return Person
     */
    public function setBirthCertificateScan(?string $birthCertificateScan): Person
    {
        $this->birthCertificateScan = $birthCertificateScan;
        return $this;
    }

    /**
     * getShortName
     *
     * @return string
     */
    public function getShortName(): string
    {
        $name = trim(mb_substr($this->getSurname(), 0, 3));
        $fn = explode($this->getFirstName(), ' ');
        foreach($fn as $n)
            $name .= mb_substr($n, 0, 1);
        return trim($name);
    }

    /**
     * @var string|null
     */
    private $ethnicity;

    /**
     * getEthnicity
     *
     * @return null|string
     */
    public function getEthnicity(): ?string
    {
        return $this->ethnicity;
    }

    /**
     * setEthnicity
     *
     * @param null|string $ethnicity
     * @return Person
     */
    public function setEthnicity(?string $ethnicity): Person
    {
        $this->ethnicity = $ethnicity;
        return $this;
    }

    /**
     * @var string|null
     */
    private $religion;

    /**
     * getReligion
     *
     * @return null|string
     */
    public function getReligion(): ?string
    {
        return $this->religion;
    }

    /**
     * setReligion
     *
     * @param null|string $religion
     * @return Person
     */
    public function setReligion(?string $religion): Person
    {
        $this->religion = $religion;
        return $this;
    }

    /**
     * @var string|null
     */
    private $citizenship1;

    /**
     * @return null|string
     */
    public function getCitizenship1(): ?string
    {
        return $this->citizenship1;
    }

    /**
     * @param null|string $citizenship1
     * @return Person
     */
    public function setCitizenship1(?string $citizenship1): Person
    {
        $this->citizenship1 = $citizenship1;
        return $this;
    }

    /**
     * @var string|null
     */
    private $citizenship1Passport;

    /**
     * @return null|string
     */
    public function getCitizenship1Passport(): ?string
    {
        return $this->citizenship1Passport;
    }

    /**
     * @param null|string $citizenship1Passport
     * @return Person
     */
    public function setCitizenship1Passport(?string $citizenship1Passport): Person
    {
        $this->citizenship1Passport = $citizenship1Passport;
        return $this;
    }

    /**
     * @var string|null
     */
    private $citizenship1PassportScan;

    /**
     * @return null|string
     */
    public function getCitizenship1PassportScan(): ?string
    {
        return $this->citizenship1PassportScan;
    }

    /**
     * @param null|string $citizenship1PassportScan
     * @return Person
     */
    public function setCitizenship1PassportScan(?string $citizenship1PassportScan): Person
    {
        $this->citizenship1PassportScan = $citizenship1PassportScan;
        return $this;
    }

    /**
     * @var string|null
     */
    private $citizenship2;

    /**
     * @return null|string
     */
    public function getCitizenship2(): ?string
    {
        return $this->citizenship2;
    }

    /**
     * @param null|string $citizenship2
     * @return Person
     */
    public function setCitizenship2(?string $citizenship2): Person
    {
        $this->citizenship2 = $citizenship2;
        return $this;
    }

    /**
     * @var string|null
     */
    private $citizenship2Passport;

    /**
     * @return null|string
     */
    public function getCitizenship2Passport(): ?string
    {
        return $this->citizenship2Passport;
    }

    /**
     * @param null|string $citizenship2Passport
     * @return Person
     */
    public function setCitizenship2Passport(?string $citizenship2Passport): Person
    {
        $this->citizenship2Passport = $citizenship2Passport;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nationalIDCard;

    /**
     * @return null|string
     */
    public function getNationalIDCard(): ?string
    {
        return $this->nationalIDCard;
    }

    /**
     * @param null|string $nationalIDCard
     * @return Person
     */
    public function setNationalIDCard(?string $nationalIDCard): Person
    {
        $this->nationalIDCard = $nationalIDCard;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nationalIDCardScan;

    /**
     * @return null|string
     */
    public function getNationalIDCardScan(): ?string
    {
        return $this->nationalIDCardScan;
    }

    /**
     * @param null|string $nationalIDCardScan
     * @return Person
     */
    public function setNationalIDCardScan(?string $nationalIDCardScan): Person
    {
        $this->nationalIDCardScan = $nationalIDCardScan;
        return $this;
    }

    /**
     * @var string|null
     */
    private $residencyStatus;

    /**
     * @return null|string
     */
    public function getResidencyStatus(): ?string
    {
        return $this->residencyStatus;
    }

    /**
     * @param null|string $residencyStatus
     * @return Person
     */
    public function setResidencyStatus(?string $residencyStatus): Person
    {
        $this->residencyStatus = $residencyStatus;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $visaExpiryDate;

    /**
     * @return null|\DateTime
     */
    public function getVisaExpiryDate(): ?\DateTime
    {
        return $this->visaExpiryDate;
    }

    /**
     * @param null|\DateTime $visaExpiryDate
     * @return Person
     */
    public function setVisaExpiryDate(?\DateTime $visaExpiryDate): Person
    {
        $this->visaExpiryDate = $visaExpiryDate;
        return $this;
    }

    /**
     * @var House|null
     */
    private $house;

    /**
     * @return House|null
     */
    public function getHouse(): ?House
    {
        return $this->house;
    }

    /**
     * @param House|null $house
     * @return Person
     */
    public function setHouse(?House $house): Person
    {
        $this->house = $house;
        return $this;
    }

    /**
     * @var string|null
     */
    private $vehicleRegistration;

    /**
     * @return null|string
     */
    public function getVehicleRegistration(): ?string
    {
        return $this->vehicleRegistration;
    }

    /**
     * @param null|string $vehicleRegistration
     * @return Person
     */
    public function setVehicleRegistration(?string $vehicleRegistration): Person
    {
        $this->vehicleRegistration = $vehicleRegistration;
        return $this;
    }

    /**
     * @var string|null
     */
    private $studentIdentifier;

    /**
     * @return null|string
     */
    public function getStudentIdentifier(): ?string
    {
        return $this->studentIdentifier;
    }

    /**
     * @param null|string $studentIdentifier
     * @return Person
     */
    public function setStudentIdentifier(?string $studentIdentifier): Person
    {
        $this->studentIdentifier = $studentIdentifier;
        return $this;
    }

    /**
     * @var string|null
     */
    private $lockerNumber;

    /**
     * @return null|string
     */
    public function getLockerNumber(): ?string
    {
        return $this->lockerNumber;
    }

    /**
     * @param null|string $lockerNumber
     * @return Person
     */
    public function setLockerNumber(?string $lockerNumber): Person
    {
        $this->lockerNumber = $lockerNumber;
        return $this;
    }

    /**
     * @var string|null
     */
    private $transport;

    /**
     * @return null|string
     */
    public function getTransport(): ?string
    {
        return $this->transport;
    }

    /**
     * @param null|string $transport
     * @return Person
     */
    public function setTransport(?string $transport): Person
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * @var string|null
     */
    private $transportNotes;

    /**
     * @return null|string
     */
    public function getTransportNotes(): ?string
    {
        return $this->transportNotes;
    }

    /**
     * @param null|string $transportNotes
     * @return Person
     */
    public function setTransportNotes(?string $transportNotes): Person
    {
        $this->transportNotes = $transportNotes;
        return $this;
    }

    /**
     * @var boolean
     */
    private $canLogin;

    /**
     * isCanLogin
     *
     * @return bool
     */
    public function isCanLogin(){
        if ($this->getUser() instanceof UserInterface)
            return $this->canLogin = true;
        return $this->canLogin = false;
    }

    /**
     * setCanLogin
     *
     * @return Person
     */
    public function setCanLogin(?bool $canLogin): Person
    {
        $this->canLogin = $canLogin ? true : false;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forcePasswordReset;

    /**
     * @return bool
     */
    public function isForcePasswordReset(): bool
    {
        if (! $this->isCanLogin())
            return $this->forcePasswordReset = false;
        $this->forcePasswordReset = $this->getUser()->isCredentialsExpired() ;
        return $this->forcePasswordReset;
    }

    /**
     * @param bool $forcePasswordReset
     * @return Person
     */
    public function setForcePasswordReset(bool $forcePasswordReset): Person
    {
        if ($this->isCanLogin()) {
            $this->getUser()->setCredentialsExpired($forcePasswordReset);
            $this->forcePasswordReset = $this->getUser()->isCredentialsExpired();
        }
        return $this;
    }

    /**
     * @var string|null
     */
    private $username;

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        if ($this->isCanLogin())
            $this->username = $this->getUser()->getUsername();

        return $this->username;
    }

    /**
     * @param null|string $username
     * @return Person
     */
    public function setUsername(?string $username): Person
    {
        if ($this->isCanLogin()) {
            if (empty($username))
                $username = $this->getEmail();
            $this->getUser()->setUsername($username);
            $this->getUser()->setUsernameCanonical($username);
        }
        $this->username = $username;
        return $this;
    }

    /**
     * @var string|null
     */
    private $profession;

    /**
     * @return null|string
     */
    public function getProfession(): ?string
    {
        return $this->profession;
    }

    /**
     * @param null|string $profession
     * @return Person
     */
    public function setProfession(?string $profession): Person
    {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @var string|null
     */
    private $employer;

    /**
     * @return null|string
     */
    public function getEmployer(): ?string
    {
        return $this->employer;
    }

    /**
     * @param null|string $employer
     * @return Person
     */
    public function setEmployer(?string $employer): Person
    {
        $this->employer = $employer;
        return $this;
    }

    /**
     * @var string|null
     */
    private $jobTitle;

    /**
     * @return null|string
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param null|string $jobTitle
     * @return Person
     */
    public function setJobTitle(?string $jobTitle): Person
    {
        $this->jobTitle = $jobTitle;
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
     * @return Person
     */
    public function setWebsite(?string $website): Person
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @var boolean|null
     */
    private $viewSchoolCalendar;

    /**
     * @return bool
     */
    public function getViewSchoolCalendar(): bool
    {
        return $this->viewSchoolCalendar ? true : false ;
    }

    /**
     * @param bool|null $viewSchoolCalendar
     * @return Person
     */
    public function setViewSchoolCalendar(?bool $viewSchoolCalendar): Person
    {
        $this->viewSchoolCalendar = $viewSchoolCalendar ? true : false;
        return $this;
    }

    /**
     * @var boolean|null
     */
    private $viewPersonalCalendar;

    /**
     * @return bool
     */
    public function getViewPersonalCalendar(): bool
    {
        return $this->viewPersonalCalendar ? true : false ;
    }

    /**
     * @param bool|null $viewPersonalCalendar
     * @return Person
     */
    public function setViewPersonalCalendar(?bool $viewPersonalCalendar): Person
    {
        $this->viewPersonalCalendar = $viewPersonalCalendar ? true : false;
        return $this;
    }

    /**
     * @var boolean|null
     */
    private $viewSpaceBookingCalendar;

    /**
     * @return bool
     */
    public function getViewSpaceBookingCalendar(): bool
    {
        return $this->viewSpaceBookingCalendar ? true : false ;
    }

    /**
     * @param bool|null $viewSpaceBookingCalendar
     * @return Person
     */
    public function setViewSpaceBookingCalendar(?bool $viewSpaceBookingCalendar): Person
    {
        $this->viewSpaceBookingCalendar = $viewSpaceBookingCalendar ? true : false;
        return $this;
    }

    /**
     * @var string|null
     */
    private $privacy;

    /**
     * @return null|string
     */
    public function getPrivacy(): ?string
    {
        return $this->privacy;
    }

    /**
     * @param null|string $privacy
     * @return Person
     */
    public function setPrivacy(?string $privacy): Person
    {
        $this->privacy = $privacy;
        return $this;
    }

    /**
     * @var string|null
     */
    private $dayType;

    /**
     * @return null|string
     */
    public function getDayType(): ?string
    {
        return $this->dayType;
    }

    /**
     * @param null|string $dayType
     * @return Person
     */
    public function setDayType(?string $dayType): Person
    {
        $this->dayType = $dayType;
        return $this;
    }

    /**
     * @var string|null
     */
    private $googleAPIRefreshToken;

    /**
     * @return null|string
     */
    public function getGoogleAPIRefreshToken(): ?string
    {
        return $this->googleAPIRefreshToken;
    }

    /**
     * @param null|string $googleAPIRefreshToken
     * @return Person
     */
    public function setGoogleAPIRefreshToken(?string $googleAPIRefreshToken): Person
    {
        $this->googleAPIRefreshToken = $googleAPIRefreshToken;
        return $this;
    }

    /**
     * @var string|null
     */
    private $studentAgreements;

    /**
     * @return null|string
     */
    public function getStudentAgreements(): ?string
    {
        return $this->studentAgreements;
    }

    /**
     * @param null|string $studentAgreements
     * @return Person
     */
    public function setStudentAgreements(?string $studentAgreements): Person
    {
        $this->studentAgreements = $studentAgreements;
        return $this;
    }

    /**
     * @var string|null
     */
    private $fields;

    /**
     * @return null|string
     */
    public function getFields(): ?string
    {
        return $this->fields;
    }

    /**
     * @param null|string $fields
     * @return Person
     */
    public function setFields(?string $fields): Person
    {
        $this->fields = $fields;
        return $this;
    }


    public function getFamilies(): Collection
    {
        $families = $this->getAdultFamilies();
        foreach($this->getChildFamilies()->getIterator() as $child)
            if(! $families->contains($child))
                $families->add($child);
        return $families;
    }
}
