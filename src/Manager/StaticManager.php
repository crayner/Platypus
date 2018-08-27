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
 * Date: 27/08/2018
 * Time: 15:59
 */
namespace App\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class StaticManager
 * @package App\Manager
 */
class StaticManager
{
    /**
     * @var Collection
     */
    private static $managers;

    /**
     * @return Collection
     */
    public static function getManagers(): Collection
    {
        if (empty(self::$managers))
            self::$managers = new ArrayCollection();

        return self::$managers;
    }

    /**
     * @param Collection $managers
     */
    public static function setManagers(Collection $managers): void
    {
        self::$managers = $managers;
    }

    /**
     * addManager
     *
     * @param object $manager
     */
    public static function addManager(object $manager): void
    {
        if (! self::getManagers()->contains($manager))
            self::getManagers()->set(get_class($manager), $manager);
    }

    /**
     * getManager
     *
     * @param string $className
     * @return null|object
     */
    public static function getManager(string $className): ?object
    {
        if (self::getManagers()->containsKey($className))
            return self::getManagers()->get($className);

        return null;
    }

    /**
     * call
     *
     * @param string $className
     * @param string $method
     * @return mixed
     */
    public static function call(string $className, string $method)
    {
        if ($manager = self::getManager($className) !== null)
        {
            if (method_exists($manager, $method))
            {
                return $manager->$method();
            } else {
                return null;
            }
        }
        return null;
    }
}