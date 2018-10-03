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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class StudentsSettings
 * @package App\Manager\Settings
 */
class StudentsSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Students';
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

        $setting = $sm->createOneByName('students.enable_student_notes');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Enable Student Notes')
           ->setDescription('Should student notes be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.note_creation_notification');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['tutors','tutors_teachers'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue('tutors')
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Note Creation Notification')
           ->setDescription('Determines who to notify when a new student note is created.');
        if (empty($setting->getValue())) {
            $setting->setValue('tutors')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Student Notes';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];
        $setting = $sm->createOneByName('students.academic_alert_low_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Low Academic Alert Threshold')
           ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a low level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(3)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.academic_alert_medium_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Medium Academic Alert Threshold')
           ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a medium level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(5)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.academic_alert_high_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('High Academic Alert Threshold')
           ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a high level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(9)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_low_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Low Behaviour Alert Threshold')
           ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a low level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(3)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_medium_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Medium Behaviour Alert Threshold')
           ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a medium level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(5)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_high_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')

            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('High Behaviour Alert Threshold')
           ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a high level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(9)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Alerts';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('students.extended_brief_profile');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Extended Brief Profile')
           ->setDescription('The extended version of the brief student profile includes contact information of parents.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('school_admin.student_agreement_options');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', ['tutors','tutors_teachers'])
            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Student Agreement Options')
           ->setDescription('List of agreements that students might be asked to sign in school (e.g. ICT Policy).');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_student_settings';

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
