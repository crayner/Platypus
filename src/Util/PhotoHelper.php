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
 * Date: 15/06/2018
 * Time: 13:11
 */
namespace App\Util;

/**
 * Class PhotoHelper
 * @package App\Util
 */
class PhotoHelper
{
    /**
     * deletePhotoFile
     *
     * @param $fileName
     */
    public static function deletePhotoFile($fileName)
    {
        if (empty($fileName))
            return ;
        if (file_exists(self::getPublicDir() . $fileName))
            unlink(self::getPublicDir() . $fileName);
    }

    /**
     * getPublicDir
     *
     * @return string
     */
    private static function getPublicDir(): string
    {
        return realpath(__DIR__.'/../../public') . DIRECTORY_SEPARATOR;
    }
}