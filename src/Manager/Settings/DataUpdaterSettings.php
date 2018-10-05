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
class DataUpdaterSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);
        $this->setSectionsHeader('data_updater_settings');

        $setting = $sm->createOneByName('data_updater.required_updates')
            ->setSettingType('boolean')
            ->setDisplayName('Required Data Updates?')
            ->setDescription('Should the data updater highlight updates that are required?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'data_updater.required_updates']);

        $setting = $sm->createOneByName('data_updater.required_updates_by_type')
            ->setSettingType('multiEnum')
            ->setDisplayName('Required Data Update Types')
            ->setChoices([
                'data_updater.required_updates_by_type.family' => 'family',
                'data_updater.required_updates_by_type.personal' => 'personal',
                'data_updater.required_updates_by_type.medical' => 'medical',
                'data_updater.required_updates_by_type.finance' => 'finance'
            ])
            ->setDescription('Which type of data updates should be required.');
        if (empty($setting->getValue()))
            $setting->setValue(['family','personal']);
        $this->addSetting($setting, ['hideParent' => 'data_updater.required_updates']);

        $setting = $sm->createOneByName('data_updater.cutoff_date')
            ->setSettingType('date')
            ->setDisplayName('Cutoff Date')
            ->setDescription('Earliest acceptable date when checking if data updates are required.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'data_updater.required_updates']);

        $setting = $sm->createOneByName('data_updater.redirect_by_role_category')
            ->setSettingType('multiEnum')
            ->setDisplayName('Data Updater Redirect')
            ->setChoices( [
                'data_updater.redirect_by_role_category.staff' => 'staff',
                'data_updater.redirect_by_role_category.student' => 'student',
                'data_updater.redirect_by_role_category.parent' => 'parent'
            ])
            ->setDescription('Which types of users should be redirected to the Data Updater if updates are required.');
        if (empty($setting->getValue()))
            $setting->setValue(['parent']);
        $this->addSetting($setting, ['hideParent' => 'data_updater.required_updates']);

        $this->addSection('Settings');

        $this->setSettingManager(null);

        return $this;
    }
}
