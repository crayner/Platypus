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
class MarkbookSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('markbook.enable_effort')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Effort')
            ->setDescription('Should columns have the Effort section enabled?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
            $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.enable_rubrics')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Rubrics')
            ->setDescription('Should columns have Rubrics section enabled?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
            $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.enable_column_weighting')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Column Weighting')
            ->setDescription('Should column weighting and total scores be enabled in the Markbook?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'markbook.enable_column_weighting']);

        $setting = $sm->createOneByName('markbook.enable_display_cumulative_marks')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Display Cumulative Marks')
            ->setDescription('Should cumulative marks be displayed on the View Markbook page for Students and Parents and in Student Profiles?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'markbook.enable_column_weighting']);

        $setting = $sm->createOneByName('markbook.enable_raw_attainment')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Raw Attainment Marks')
            ->setDescription('Should recording of raw marks be enabled in the Markbook?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Features');

        $setting = $sm->createOneByName('markbook.markbook_type')
            ->setSettingType('array')
            ->setValidators([
                new NotBlank(),
                new Yaml(),
            ])
            ->setDisplayName('Markbook Type')
            ->setDescription('List of types to make available in the Markbook.');
        if (empty($setting->getValue()))
            $setting->setValue(['Essay','Exam','Homework','Reflection','Test','Unit','End of Year','Other']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.enable_group_by_term')
            ->setSettingType('boolean')
            ->setDisplayName('Group Columns by Term')
            ->setDescription('Should columns and total scores be grouped by term?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.attainment_alternative_name')
            ->setSettingType('string')
            ->setDisplayName('Attainment Alternative Name')
            ->setDescription('A name to use instead of "Attainment" in the first grade column of the markbook.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.attainment_alternative_name_abbrev')
            ->setSettingType('string')
            ->setDisplayName('Attainment Alternative Name Abbreviation')
            ->setDescription('A short name to use instead of "Attainment" in the first grade column of the markbook.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.effort_alternative_name')
            ->setSettingType('string')
            ->setDisplayName('Effort Alternative Name')
            ->setDescription('A name to use instead of "Effort" in the second grade column of the markbook.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.effort_alternative_name_abbrev')
            ->setSettingType('string')
            ->setDisplayName('Effort Alternative Name Abbreviation')
            ->setDescription('A short name to use instead of "Effort" in the second grade column of the markbook.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Interfaces');

        $setting = $sm->createOneByName('markbook.show_student_attainment_warning')
            ->setSettingType('boolean')
            ->setDisplayName('Show Student Attainment Warning')
            ->setDescription('Show low attainment grade visual warning to students?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.show_student_effort_warning')
            ->setSettingType('boolean')
            ->setDisplayName('Show Student Effort Warning')
            ->setDescription('Show low effort grade visual warning to students?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.show_parent_attainment_warning')
            ->setSettingType('boolean')
            ->setDisplayName('Show Parent Attainment Warning')
            ->setDescription('Show low attainment grade visual warning to parents?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.show_parent_effort_warning')
            ->setSettingType('boolean')
            ->setDisplayName('Show Parent Effort Warning')
            ->setDescription('Show low effort grade visual warning to parents?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('markbook.personalised_warnings')
            ->setSettingType('boolean')
            ->setDisplayName('Personalised Warnings')
            ->setDescription('Should markbook warnings be based on personal targets, if they are available?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $this->addSection('Warnings');

        $this->setSectionsHeader('manage_markbook_settings');

        $this->setSettingManager(null);

        return $this;
    }
}
