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
 * Time: 21:37
 */
namespace App\Composer;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;


class Installation
{
    public static function postInstall(Event $event)
    {
        //$composer = $event->getComposer();
        // do stuff

        $content = file_get_contents(__DIR__ . '/../../config/packages/platypus.yaml.dist');
        file_put_contents(__DIR__ . '/../../config/packages/platypus.yaml', $content);

        $content = file_get_contents(__DIR__ . '/../../.env.dist');
        file_put_contents(__DIR__ . '/../../.env', $content);

    }
}