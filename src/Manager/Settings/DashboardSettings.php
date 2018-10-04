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
 * Date: 22/06/2018
 * Time: 16:15
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Class DashboardSettings
 * @package App\Manager\Settings
 */
class DashboardSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Dashboard';
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

        $setting = $sm->createOneByName('school_admin.staff_dashboard_default_tab')
            ->setSettingType('enum')
            ->setDisplayName('Staff Dashboard Default Tab')
            ->setChoices([
                'dashboard_default_tab.' => '',
                'dashboard_default_tab.planner' => 'planner'
            ])
            ->setDescription('The default landing tab for the staff dashboard.');
        if (empty($setting->getValue()))
            $setting->setValue('');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('school_admin.student_dashboard_default_tab')
            ->setSettingType('enum')
            ->setDisplayName('Student Dashboard Default Tab')
            ->setChoices([
                'dashboard_default_tab.' => '',
                'dashboard_default_tab.planner' => 'planner'
            ])
            ->setDescription('The default landing tab for the student dashboard.');
        if (empty($setting->getValue()))
            $setting->setValue('');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('school_admin.parent_dashboard_default_tab')
            ->setSettingType('enum')
            ->setDisplayName('Parent Dashboard Default Tab')
            ->setChoices([
                'dashboard_default_tab.' => '',
                'dashboard_default_tab.learning_overview' => 'learning_overview',
                'dashboard_default_tab.timetable' => 'timetable',
                'dashboard_default_tab.activities' => 'activities'
            ])
            ->setDescription('The default landing tab for the parent dashboard.');
        if (empty($setting->getValue()))
            $setting->setValue('');
        $this->addSetting($setting, []);

        $this->addSection('Dashboard Settings');
        $this->setSectionsHeader('manage_dashboard_settings');

        $this->setSettingManager(null);
        
        return $this;
    }
}
