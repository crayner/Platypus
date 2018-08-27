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
}
