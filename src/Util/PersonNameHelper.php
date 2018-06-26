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
 * Date: 31/05/2018
 * Time: 08:12
 */
namespace App\Util;

use App\Entity\Person;
use App\Manager\UserManager;
use Symfony\Component\Security\Core\User\UserInterface;

class PersonNameHelper
{
    /**
     * @var Person
     */
    private static $person;

    /**
     * @return Person
     */
    public static function getPerson(): Person
    {
        return self::$person;
    }

    /**
     * @param Person $person
     */
    public static function setPerson(Person $person): void
    {
        self::$person = self::checkPerson($person);
    }

    /**
     * @param Person|null $person
     *
     * @return Person
     */
    private static function checkPerson(Person $person = null): Person
    {
        if ($person instanceof Person) {
            self::$person = $person;

            return self::$person;
        }

        if (self::$person instanceof Person)
            return self::$person;

        self::$person = self::getPerson();

        return self::$person;
    }

    /**
     * getFullName
     *
     * @param Person|array|null $person
     * @param array $options
     * @return string
     */
    public static function getFullName($person = null, array $options = []): string
    {
        $person = $person ?: self::getPerson();

        if (empty($person))
            return 'No Name';

        $options['surnameFirst']  = !isset($options['surnameFirst']) ? true : $options['surnameFirst'];
        $options['preferredOnly'] = !isset($options['preferredOnly']) ? false : $options['preferredOnly'];

        if ($person instanceof Person) {
            if (empty($person->getSurname())) return '';

            if ($options['surnameFirst']) {
                if ($options['preferredOnly'])
                    return $person->getSurname() . ': ' . $person->getPreferredName();

                return $person->getSurname() . ': ' . $person->getFirstName() . ' (' . $person->getPreferredName() . ')';
            }

            if ($options['preferredOnly'])
                return $person->getPreferredName() . ' ' . $person->getSurname();

            return $person->getFirstName() . ' (' . $person->getPreferredName() . ') ' . $person->getSurname();
        }

        if (empty($person['surname'])) return '';

        if ($options['surnameFirst']) {
            if ($options['preferredOnly'])
                return $person['surname'] . ': ' . $person['preferredName'];

            return $person['surname'] . ': ' . $person['firstName'] . ' (' . $person['preferredName'] . ')';
        }

        if ($options['preferredOnly'])
            return $person['preferredName'] . ' ' . $person['surname'];

        return $person['firstName'] . ' (' . $person['preferredName'] . ') ' . $person['surname'];

    }

    /**
     * getFullUserName
     *
     * @param UserInterface $user
     * @return string
     */
    public static function getFullUserName(?UserInterface $user): string
    {
        if (! $user instanceof UserInterface)
            if (self::getUserManager()->getUser())
                $user = self::getUserManager()->getUser();

        if (self::getUserManager()->hasPerson($user)) {
            $person = self::getUserManager()->getPerson($user);

            if ($person instanceof Person)
                return self::getFullName($person);
        }
        if ($user instanceof UserInterface)
            return $user->formatName();

        return 'No User Name' ;
    }

    /**
     * @var UserManager
     */
    private static $userManager;

    /**
     * @return UserManager
     */
    public static function getUserManager(): UserManager
    {
        return self::$userManager;
    }

    /**
     * PersonNameHelper constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, UserHelper $userHelper)
    {
        self::$userManager = $userManager;
    }

    /**
     * @param UserManager $userManager
     */
    public static function setUserManager(UserManager $userManager): void
    {
        self::$userManager = $userManager;
    }
}