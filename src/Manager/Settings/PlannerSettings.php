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
     * @param SettingManager $sm
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {
        $settings = [];

        $setting = $sm->createOneByName('planner.lesson_details_template');

        $setting->setName('planner.lesson_details_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Lesson Details Template')
           ->setDescription('Template to be inserted into Lesson Details field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.teachers_notes_template');

        $setting->setName('planner.teachers_notes_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Teacher\'s Notes Template')
           ->setDescription('Template to be inserted into Teacher\'s Notes field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.unit_outline_template');

        $setting->setName('planner.unit_outline_template')
            ->__set('role', 'ROLE_PRINCIPAL')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setSettingType('html')
            ->setDisplayName('Unit Outline Template')
           ->setDescription('Template to be inserted into Unit Outline section of planner');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.smart_block_template');

        $setting->setName('planner.smart_block_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Smart Block Template')
           ->setDescription('Template to be inserted into new block in Smart Unit');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
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
            ->setSettingType('boolean')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Make Units Public')
           ->setDescription('Enables a public listing of units, with teachers able to opt in to share units.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.share_unit_outline');

        $setting->setName('planner.share_unit_outline')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Share Unit Outline')
           ->setDescription('Allow users who do not have access to the unit planner to see Unit Outlines via the lesson planner?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.allow_outcome_editing');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Allow Outcome Editing')
           ->setDescription('Should the text within outcomes be editable when planning lessons and units?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_parents');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Sharing Default: Parents')
           ->setDescription('When adding lessons and deploying units, should sharing default for parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_students');

        $setting->setName('planner.sharing_default_students')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->setDisplayName('Sharing Default: Students')
           ->setDescription('When adding lessons and deploying units, should sharing default for students?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)

                ->setValidators(null)
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
            ->setSettingType('boolean')

            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Parent Weekly Email Summary Include Behaviour')
           ->setDescription('Should behaviour information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_markbook');

        $setting->setName('planner.parent_weekly_email_summary_include_markbook')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Parent Weekly Email Summary Include Markbook')
           ->setDescription('Should Markbook information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_planner_settings';

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
