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
 * Date: 18/06/2018
 * Time: 11:35
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Interfaces SettingCreationInterface
 * @package App\Manager\Settings
 */
interface SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string;

    /**
     * getSections
     *
     * @return array
     */
    public function getSections(): array;

    /**
     * getSettings
     *
     * @param SettingManager $sm
     * @return array
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface;
}