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
 * Time: 11:34
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Class PlannerSettings
 * @package App\Manager\Settings
 */
class PlannerSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Planner';
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
        $setting = $sm->createOneByName('planner.lesson_details_template')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDisplayName('Lesson Details Template')
            ->setDescription('Template to be inserted into Lesson Details field');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.teachers_notes_template')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDisplayName('Teacher\'s Notes Template')
            ->setDescription('Template to be inserted into Teacher\'s Notes field');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);


        $setting = $sm->createOneByName('planner.unit_outline_template')
            ->setValidators(null)
            ->setSettingType('html')
            ->setDisplayName('Unit Outline Template')
            ->setDescription('Template to be inserted into Unit Outline section of planner');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);


        $setting = $sm->createOneByName('planner.smart_block_template')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDisplayName('Smart Block Template')
            ->setDescription('Template to be inserted into new block in Smart Unit');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Planner Templates');

        $setting = $sm->createOneByName('planner.make_units_public')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Make Units Public')
            ->setDescription('Enables a public listing of units, with teachers able to opt in to share units.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.share_unit_outline')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Share Unit Outline')
            ->setDescription('Allow users who do not have access to the unit planner to see Unit Outlines via the lesson planner?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.allow_outcome_editing')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Allow Outcome Editing')
            ->setDescription('Should the text within outcomes be editable when planning lessons and units?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.sharing_default_parents')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Sharing Default: Parents')
            ->setDescription('When adding lessons and deploying units, should sharing default for parents?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.sharing_default_students')
            ->setSettingType('boolean')
            ->setDisplayName('Sharing Default: Students')
            ->setDescription('When adding lessons and deploying units, should sharing default for students?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $this->addSection('Access Settings');

        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_behaviour')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Parent Weekly Email Summary Include Behaviour')
            ->setDescription('Should behaviour information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_markbook')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Parent Weekly Email Summary Include Markbook')
            ->setDescription('Should Markbook information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Miscellaneous');

        $this->setSectionsHeader('Planner Settings');

        $this->setSettingManager(null);
        return $this;
    }
}
