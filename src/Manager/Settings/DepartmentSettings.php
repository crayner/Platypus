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
 * Date: 24/06/2018
 * Time: 09:27
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Class DepartmentSettings
 * @package App\Manager\Settings
 */
class DepartmentSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Department';
    }

    /**
     * getSettings
     *
     * @param SettingManager $sm
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('departments.make_departments_public')
            ->setSettingType('boolean')
            ->setDisplayName('Make Departments Public')
            ->setDescription('Should department information be made available to the public, via the Gibbon homepage?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Department Access');
        $this->setSectionsHeader('manage_departments');

        $this->setSettingManager(null);

        return $this;
    }
}
