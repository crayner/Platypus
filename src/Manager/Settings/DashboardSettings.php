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
class DashboardSettings implements SettingCreationInterface
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
        $settings = [];

        $setting = $sm->createOneByName('school_admin.staff_dashboard_default_tab');

        $setting->setName('school_admin.staff_dashboard_default_tab')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Staff Dashboard Default Tab')
            ->__set('choice', ['','planner'])
            ->__set('description', 'The default landing tab for the staff dashboard.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('school_admin.student_dashboard_default_tab');

        $setting->setName('school_admin.student_dashboard_default_tab')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Student Dashboard Default Tab')
            ->__set('choice', ['','planner'])
            ->__set('description', 'The default landing tab for the student dashboard.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('school_admin.parent_dashboard_default_tab');

        $setting->setName('school_admin.parent_dashboard_default_tab')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Parent Dashboard Default Tab')
            ->__set('choice', ['','learning_overview','timetable','activities'])
            ->__set('description', 'The default landing tab for the parent dashboard.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Dashboard Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_dashboard_settings';

        $this->sections = $sections;
        return $this;
    }

    /**
     * @var array
     */
    private $sections;

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }
}
