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
 * Date: 1/08/2018
 * Time: 11:34
 */
namespace App\Util;

/**
 * Class InternationalHelper
 * @package App\Util
 */
class InternationalHelper
{
    /**
     * getTimezones
     *
     * @return array
     */
    public static function getTimezones(int $regions = \DateTimeZone::ALL): array
    {
        $timezones = array();

        foreach (\DateTimeZone::listIdentifiers($regions) as $timezone) {
            $parts = explode('/', $timezone);

            if (count($parts) > 2) {
                $region = $parts[0];
                $name = $parts[1].' - '.$parts[2];
            } elseif (count($parts) > 1) {
                $region = $parts[0];
                $name = $parts[1];
            } else {
                $region = 'Other';
                $name = $parts[0];
            }

            $timezones[$region][str_replace('_', ' ', $name)] = $timezone;
        }

        return 1 === count($timezones) ? reset($timezones) : $timezones;
    }
}