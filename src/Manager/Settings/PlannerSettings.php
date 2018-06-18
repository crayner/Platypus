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
class PlannerSettings implements SettingCreationInterface
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
     * @return array
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('planner.lesson_details_template');

        $setting->setName('planner.lesson_details_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Lesson Details Template')
            ->__set('description', 'Template to be inserted into Lesson Details field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.teachers_notes_template');

        $setting->setName('planner.teachers_notes_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Teacher\'s Notes Template')
            ->__set('description', 'Template to be inserted into Teacher\'s Notes field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.unit_outline_template');

        $setting->setName('planner.unit_outline_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Unit Outline Template')
            ->__set('description', 'Template to be inserted into Unit Outline section of planner');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.smart_block_template');

        $setting->setName('planner.smart_block_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Smart Block Template')
            ->__set('description', 'Template to be inserted into new block in Smart Unit');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Planner Templates';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];


        $setting = $sm->createOneByName('planner.make_units_public');

        $setting->setName('planner.make_units_public')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Make Units Public')
            ->__set('description', 'Enables a public listing of units, with teachers able to opt in to share units.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.share_unit_outline');

        $setting->setName('planner.share_unit_outline')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Share Unit Outline')
            ->__set('description', 'Allow users who do not have access to the unit planner to see Unit Outlines via the lesson planner?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.allow_outcome_editing');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Allow Outcome Editing')
            ->__set('description', 'Should the text within outcomes be editable when planning lessons and units?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_parents');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Sharing Default: Parents')
            ->__set('description', 'When adding lessons and deploying units, should sharing default for parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_students');

        $setting->setName('planner.sharing_default_students')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Sharing Default: Students')
            ->__set('description', 'When adding lessons and deploying units, should sharing default for students?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Access Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];


        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_behaviour');

        $setting->setName('planner.parent_weekly_email_summary_include_behaviour')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Parent Weekly Email Summary Include Behaviour')
            ->__set('description', 'Should behaviour information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections['header'] = 'manage_planner_settings';

        return $sections;
    }
}