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
 * Date: 11/11/2018
 * Time: 10:13
 */
namespace App\Util;

use App\Manager\TimetableManager;
use Hillrange\Security\Util\ParameterInjector;

/**
 * Class TimetableHelper
 * @package App\Util
 */
class TimetableHelper
{
    /**
     * @var TimetableManager
     */
    private static $manager;

    /**
     * @var string|null
     */
    private static $timezone;

    /**
     * TimetableHelper constructor.
     * @param TimetableManager $manager
     */
    public function __construct(TimetableManager $manager, ParameterInjector $parameterInjector){
        self::$manager = $manager;
        self::$timezone = $parameterInjector->getParameter('timezone');
    }

    /**
     * isSchoolDay
     *
     * @param null $date  Assumes today
     * @param string $timezone  Assumes system timezone
     * @return bool
     */
    public static function isSchoolDay($date = null, $timezone = 'UTC'): bool
    {
        if (self::$timezone && self::$timezone !== $timezone)
            $timezone = self::$timezone;
        return self::getManager()->isSchoolDay($date, $timezone);
    }

    /**
     * getManager
     *
     * @return TimetableManager
     */
    public static function getManager(): TimetableManager
    {
        return self::$manager;
    }
}