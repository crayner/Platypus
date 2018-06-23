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
}