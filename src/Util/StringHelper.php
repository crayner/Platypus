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
 * Date: 25/06/2018
 * Time: 13:05
 */
namespace App\Util;

/**
 * Class StringHelper
 * @package App\Util
 */
class StringHelper
{
    /**
     * camelCase
     *
     * @param $value
     * @return string
     */
    public static function camelCase($value): string
    {
        $value = str_replace(['_', '.'], ',', $value);
        $value = explode(',', $value);
        $result = '';
        foreach($value as $item)
            $result .= ucfirst(strtolower(trim(str_replace(' ', '', $item))));

        return lcfirst($result);
    }

    /**
     * safeString
     *
     * @param $value
     * @return string
     */
    public static function safeString($value): string
    {
        $value = str_replace(' ', '_', $value);
        for($i=ord('A'); $i<=ord('Z'); $i++)
            $value = str_replace(chr($i), '_'.chr($i+32), $value);
        $value = trim(str_replace('__', '_', $value), '_');
        return $value;
    }
}