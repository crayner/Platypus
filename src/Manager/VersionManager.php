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
 * Date: 8/06/2018
 * Time: 16:44
 */

namespace App\Manager;


use Doctrine\ORM\Tools\SchemaTool;

class VersionManager
{
    /**
     * @var string
     */
    const VERSION = '0.1.02';

    /**
     * @param $version
     *
     * @return string
     */
    public static function incrementVersion($version)
    {
        $parts = explode('.', $version);
        if (count($parts) !== 3)
        {
            trigger_error('This process only accepts standard 3 part versions. (0.0.00)');
            return $version;
        }

        if ($parts[2] + 1 < 99) {
            $parts[2]++;
        } else {
            $parts[2] = 0;
            if ($parts[1] + 1 <= 99) {
                $parts[1]++;
            } else {
                $parts[1] = 0;
                $parts[0]++;
            }
        }

        $parts[2] = str_pad($parts[2], 2, '0', STR_PAD_LEFT);

        return implode('.', $parts);
    }
}