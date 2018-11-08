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
 * Date: 16/08/2018
 * Time: 10:27
 */
namespace App\Util;

use App\Entity\Person;
use App\Entity\Staff;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PersonHelper
 * @package App\Util
 */
class PersonHelper
{
    /**
     * @var EntityManagerInterface
     */
    private static $entityManager;

    /**
     * PersonHelper constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @var UserInterface|null
     */
    private static $user;

    /**
     * @var Person|null
     */
    private static $person;

    /**
     * hasPerson
     *
     * @return bool
     */
    public static function hasPerson(?Person $person = null): bool
    {
        if ($person instanceof Person)
            self::setPerson($person);

        if (self::$person instanceof Person)
            return true;

        if (self::hasUser())
            self::$person = self::getRepository(Person::class)->findOneByUser(self::getUser());


        if (self::$person instanceof Person)
            return true;

        return false;
    }

    /**
     * getPerson
     *
     * @return Person|null
     */
    public static function getPerson(): ?Person
    {
        if (is_null(self::$person))
            self::hasPerson();
        return self::$person;
    }

    /**
     * setPerson
     *
     * @param Person|null $person
     */
    public static function setPerson($person): void
    {
        if (is_int($person))
            $person = self::getRepository(Person::class)->find($person);

        self::$person = $person;
    }

    /**
     * hasUser
     *
     * @return bool
     */
    public static function hasUser(): bool
    {
        if (self::$user instanceof UserInterface)
            return true;
        if (self::getUser() instanceof UserInterface)
        {
            self::setUser(null);
            return true;
        }
        return false;
    }

    /**
     * @return null|UserInterface
     */
    public static function getUser(): ?UserInterface
    {
        return self::$user ?: UserHelper::getCurrentUser();
    }

    /**
     * @param null|UserInterface $user
     */
    public static function setUser(?UserInterface $user): void
    {
        self::$user = $user ?: UserHelper::getCurrentUser();
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    public static function getEntityManager(): EntityManagerInterface
    {
        return self::$entityManager;
    }

    /**
     * getRepository
     *
     * @param string $class
     * @return ObjectRepository
     */
    public static function getRepository(string $class): ObjectRepository
    {
        return self::$entityManager->getRepository($class);
    }

    /**
     * @var Staff|null
     */
    private static $staff;

    /**
     * getStaff
     *
     * @return Staff|null
     */
    public static function getStaff(): ?Staff
    {
        if (is_null(self::$staff) && self::hasPerson())
        {

        }
        return self::$staff;
    }

    /**
     * setStaff
     *
     * @param Staff|null $staff
     */
    public static function setStaff(?Staff $staff): void
    {
        self::$staff = $staff;
    }

    /**
     * hasStaff
     *
     * @return bool
     */
    public static function hasStaff(?Person $person = null): bool
    {
        if ($person instanceof Person)
            self::setPerson($person);

        if (self::$staff instanceof Staff)
            return true;

        $staff = null;
        if (self::hasPerson())
            $staff = self::getPerson()->getStaff();

        self::setStaff($staff);
        if (self::$staff instanceof Staff)
            return true;

        return false;
    }

    /**
     * getPhoto
     *
     * @param int $size
     * @param string $float
     * @return string
     */
    public static function getPhoto($size = 75, $float = 'none')
    {
        PersonNameHelper::setPerson(self::getPerson());
        $photo = PersonNameHelper::getFullName();

        if (empty(self::getPerson()) || empty(self::getPerson()->getPhoto()))
            self::getPerson()->setPhoto(self::getBlankPhoto());
        if (is_string(self::getPerson()->getPhoto()) && file_exists(self::getPerson()->getPhoto()))
        {
            $photo = '<img class="img-thumbnail img-photo' . $size . '" title="' . $photo . '" src="/' . self::getPerson()->getPhoto() . '" width="' . $size . '" style="float: ' . $float . '" />';
        }

        return $photo;
    }

    /**
     * getBlankPhoto
     *
     * @return string
     */
    private static function getBlankPhoto(): string
    {
        $photo = 'build/static/images/DefaultPerson.png';

        return $photo;
    }
}