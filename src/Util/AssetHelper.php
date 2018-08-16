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
 * Time: 13:27
 */
namespace App\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AssetHelper
 * @package App\Util
 */
class AssetHelper
{
    /**
     * @var string
     */
    private static $uploadDir;

    /**
     * @var string
     */
    private static $publicDir;

    /**
     * AssetHelper constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        self::$publicDir = realpath($container->getParameter('kernel.project_dir'). DIRECTORY_SEPARATOR . 'public' );
        self::$uploadDir = realpath(self::$publicDir . DIRECTORY_SEPARATOR . $container->getParameter('upload_path'));
    }

    /**
     * removeAsset
     *
     * @param null|string $asset
     */
    public static function removeAsset(?string $asset): void
    {
        if (empty($asset))
            return ;

        if (strpos($asset, 'upload') === false)
            return ;
        $file = self::$publicDir . DIRECTORY_SEPARATOR . $asset;

        if (is_file($file))
            unlink($file);
    }
}