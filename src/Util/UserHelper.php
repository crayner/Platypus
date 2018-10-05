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
 * Date: 13/06/2018
 * Time: 16:27
 */
namespace App\Util;

use App\Entity\SchoolYear;
use App\Repository\SchoolYearRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserHelper
{
    /**
     * @var TokenStorageInterface
     */
    private static $tokenStorage;

    /**
     * @var SchoolYearRepository
     */
    private static $schoolYearRepository;

    /**
     * @var SchoolYear|null
     */
    private static $currentSchoolYear;

    /**
     * UserHelper constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage, SchoolYearRepository $schoolYearRepository)
    {
        self::$tokenStorage = $tokenStorage;
        self::$schoolYearRepository = $schoolYearRepository;
    }

    /**
     * @var UserInterface|null
     */
    private static $currentUser;

    /**
     * getCurrentUser
     *
     */
    public static function getCurrentUser(): ?UserInterface
    {
        if (! is_null(self::$currentUser))
            return self::$currentUser;

        $token = self::$tokenStorage->getToken();

        if (is_null($token))
            return null;

        $user = $token->getUser();
        if ($user instanceof UserInterface)
            self::$currentUser = $user;
        else
            self::$currentUser = null;

        return self::$currentUser;
    }

    /**
     * getCurrentSchoolYear
     * @deprecated
     * @return SchoolYear|null
     */
    public static function getCurrentSchoolYear(): ?SchoolYear
    {
        trigger_error('Please use SchoolYearHelper::getCurrentSchoolYear', E_USER_DEPRECATED);

        return SchoolYearHelper::getCurrentSchoolYear();
    }

    /**
     * @param SchoolYear|null $schoolYear
     * @return null|SchoolYear
     */
    public static function getNextSchoolYear(?SchoolYear $schoolYear): ?SchoolYear
    {
        trigger_error('Please use SchoolYearHelper::getNextSchoolYear', E_USER_DEPRECATED);
        return SchoolYearHelper::getNextSchoolYear();
    }

    /**
     * @var SchoolYear
     */
    private static $nextSchoolYear;

    /**
     * @return SchoolYearRepository
     */
    public static function getSchoolYearRepository(): SchoolYearRepository
    {
        return self::$schoolYearRepository;
    }
}