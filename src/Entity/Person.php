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
use Hillrange\Security\Util\ParameterInjector;
use Symfony\Component\Security\Core\User\UserInterface;

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
        return self::$titleList;
    }

    /**
     * @return array
     */
    public static function getGenderList(): array
    {
        return ParameterInjector::getParameter('personal.title.list');
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
     * @var array
     */
    private static $titleList = [
        'm',
        'f',
        'o',
        'u',
    ];

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return Person
     */
    public function setTitle(?string $title): Person
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @var string|null
     */
    private $gender;

    private static $genderList = [];

    /**
     * @return null|string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param null|string $gender
     * @return Person
     */
    public function setGender(?string $gender): Person
    {
        $this->gender = $gender;
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
     * @var \DateTime|null
     */
    private $email;

    /**
     * @return \DateTime|null
     */
    public function getEmail(): ?\DateTime
    {
        return $this->email;
    }

    /**
     * @param \DateTime|null $email
     * @return Person
     */
    public function setEmail(?\DateTime $email): Person
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

        if (! empty($department))
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
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return PersonNameHelper::getFullName($this);
    }
}