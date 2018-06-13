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

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class UserHelper
{
    /**
     * @var TokenStorageInterface
     */
    private static $tokenStorage;

    /**
     * UserHelper constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        self::$tokenStorage = $tokenStorage;
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

}