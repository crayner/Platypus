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
    private $families;

    /**
     * @return Collection
     */
    public function getFamilies(): Collection
    {
        if (empty($this->families))
            $this->familes = new ArrayCollection();

        if ($this->families instanceof PersistentCollection)
            $this->families->initialize();

        return $this->families;
    }

    /**
     * @param Collection $families
     * @return Person
     */
    public function setFamilies(Collection $families): Person
    {
        $this->families = $families;
        return $this;
    }

    /**
     * addFamily
     *
     * @param FamilyPerson|null $family
     * @param bool $add
     * @return Person
     */
    public function addFamily(?FamilyPerson $family, bool $add = true): Person
    {
        if (empty($family) || $this->getFamilies()->contains($family))
            return $this;

        if ($add)
            $family->setPerson($this, false);

        $this->families->add($family);

        return $this;
    }

    /**
     * removeFamily
     *
     * @param FamilyPerson|null $family
     * @return Person
     */
    public function removeFamily(?FamilyPerson $family): Person
    {
        if (empty($family))
            return $this;

        $family->setPerson(null);
        $this->getFamilies()->removeElement($family);

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
}
