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
 * Time: 20:33
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Validator\Yaml;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class MarkbookSettings
 * @package App\Manager\Settings
 */
class MarkbookSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Markbook';
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

        $setting = $sm->createOneByName('markbook.enable_effort');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Effort')
            ->__set('description', 'Should columns have the Effort section enabled?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.enable_rubrics');

        $setting->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Rubrics')
            ->__set('description', 'Should columns have Rubrics section enabled?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.enable_column_weighting');

        $setting->setName('markbook.enable_column_weighting')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('displayName', 'Enable Column Weighting')
            ->__set('description', 'Should column weighting and total scores be enabled in the Markbook?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $setting->setHideParent('markbook.enable_column_weighting');
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.enable_display_cumulative_marks');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Display Cumulative Marks')
            ->__set('description', 'Should cumulative marks be displayed on the View Markbook page for Students and Parents and in Student Profiles?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $setting->setHideParent('markbook.enable_column_weighting');
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.enable_raw_attainment');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Raw Attainment Marks')
            ->__set('description', 'Should recording of raw marks be enabled in the Markbook?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Features';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('markbook.markbook_type');

        $setting->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators([
                new NotBlank(),
                new Yaml(),
            ])
            ->setDefaultValue(['essay','exam','homework','reflection','test','unit','end_of_year','other'])
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Markbook Type')
            ->__set('description', 'List of types to make available in the Markbook.');
        if (empty($setting->getValue())) {
            $setting->setValue(['essay','exam','homework','reflection','test','unit','end_of_year','other'])
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.enable_group_by_term');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Group Columns by Term')
            ->__set('description', 'Should columns and total scores be grouped by term?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.attainment_alternative_name');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Attainment Alternative Name')
            ->__set('description', 'A name to use instead of "Attainment" in the first grade column of the markbook.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.attainment_alternative_name_abbrev');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Attainment Alternative Name Abbreviation')
            ->__set('description', 'A short name to use instead of "Attainment" in the first grade column of the markbook.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.effort_alternative_name');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Effort Alternative Name')
            ->__set('description', 'A name to use instead of "Effort" in the second grade column of the markbook.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.effort_alternative_name_abbrev');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Effort Alternative Name Abbreviation')
            ->__set('description', 'A short name to use instead of "Effort" in the second grade column of the markbook.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Interfaces';
        $section['description'] = '';
        $section['settings'] = $settings;

        $settings = [];
        $sections[] = $section;

        $setting = $sm->createOneByName('markbook.show_student_attainment_warning');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Show Student Attainment Warning')
            ->__set('description', 'Show low attainment grade visual warning to students?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.show_student_effort_warning');

        $setting->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Show Student Effort Warning')
            ->__set('description', 'Show low effort grade visual warning to students?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.show_parent_attainment_warning');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Show Parent Attainment Warning')
            ->__set('description', 'Show low attainment grade visual warning to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.show_parent_effort_warning');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Show Parent Effort Warning')
            ->__set('description', 'Show low effort grade visual warning to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('markbook.personalised_warnings');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Personalised Warnings')
            ->__set('description', 'Should markbook warnings be based on personal targets, if they are available?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Warnings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_markbook_settings';

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
