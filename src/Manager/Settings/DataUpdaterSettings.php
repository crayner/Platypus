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
 * Date: 20/09/2018
 * Time: 11:26
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Class DataUpdaterSettings
 * @package App\Manager\Settings
 */
class DataUpdaterSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'DataUpdater';
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
        $sections = [];
        $sections['header'] = 'data_updater_settings';
        $settings = [];

        $setting = $sm->createOneByName('data_updater.required_updates');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->setDisplayName('Required Data Updates?')

            ->setValidators(null)
            ->setDefaultValue(false)
             ->setDescription('Should the data updater highlight updates that are required?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $setting->setHideParent('data_updater.required_updates');
        $settings[] = $setting;

        $setting = $sm->createOneByName('data_updater.required_updates_by_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('multiChoice')
            ->setDisplayName('Required Data Update Types')
            ->__set('choice', ['family','personal','medical','finance'])
            ->setValidators(null)
            ->setDefaultValue(['family','personal'])
            ->__set('translateChoice', 'System')
           ->setDescription('Which type of data updates should be required.');
        if (empty($setting->getValue())) {
            $setting->setValue(['family','personal']);
        }
        $setting->setHideParent('data_updater.required_updates');
        $settings[] = $setting;

        $setting = $sm->createOneByName('data_updater.cutoff_date');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('date')
            ->setDisplayName('Cutoff Date')

            ->setValidators(null)
           ->setDescription('Earliest acceptable date when checking if data updates are required.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('data_updater.required_updates');
        $settings[] = $setting;

        $setting = $sm->createOneByName('data_updater.redirect_by_role_category');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('multiChoice')
            ->setDisplayName('Data Updater Redirect')
            ->__set('choice', ['staff','student','parent'])
            ->setValidators(null)
            ->setDefaultValue(['parent'])
            ->__set('translateChoice', 'System')
           ->setDescription('Which types of users should be redirected to the Data Updater if updates are required.');
        if (empty($setting->getValue())) {
            $setting->setValue(['parent']);
        }
        $setting->setHideParent('data_updater.required_updates');
        $settings[] = $setting;

        $section['name'] = 'Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $this->sections = $sections;
        $settings = [];

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
