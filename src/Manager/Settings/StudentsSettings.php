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
     * @return array
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('students.enable_student_notes');

        $setting->setName('students.enable_student_notes')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Student Notes')
            ->__set('description', 'Should student notes be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.note_creation_notification');

        $setting->setName('students.note_creation_notification')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Note Creation Notification')
            ->__set('description', 'Determines who to notify when a new student note is created.');
        if (empty($setting->getValue())) {
            $setting->setValue('tutors')
                ->__set('choice', 'tutors,tutors_teachers')
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue('tutors')
                ->__set('translateChoice', 'Setting')
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

        $setting->setName('students.academic_alert_low_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'Low Academic Alert Threshold')
            ->__set('description', 'The number of Markbook concerns needed in the past 60 days to raise a low level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(3)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(3)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.academic_alert_medium_threshold');

        $setting->setName('students.academic_alert_medium_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'Medium Academic Alert Threshold')
            ->__set('description', 'The number of Markbook concerns needed in the past 60 days to raise a medium level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(5)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(5)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.academic_alert_high_threshold');

        $setting->setName('students.academic_alert_high_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'High Academic Alert Threshold')
            ->__set('description', 'The number of Markbook concerns needed in the past 60 days to raise a high level academic alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(9)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(9)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_low_threshold');

        $setting->setName('students.behaviour_alert_low_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'Low Behaviour Alert Threshold')
            ->__set('description', 'The number of Behaviour concerns needed in the past 60 days to raise a low level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(3)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(3)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_medium_threshold');

        $setting->setName('students.behaviour_alert_medium_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'Medium Behaviour Alert Threshold')
            ->__set('description', 'The number of Behaviour concerns needed in the past 60 days to raise a medium level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(5)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(5)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.behaviour_alert_high_threshold');

        $setting->setName('students.behaviour_alert_high_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'High Behaviour Alert Threshold')
            ->__set('description', 'The number of Behaviour concerns needed in the past 60 days to raise a high level Behaviour alert on a student.');
        if (empty($setting->getValue())) {
            $setting->setValue(9)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 50])
                    ]
                )
                ->setDefaultValue(9)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Alerts';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('students.extended_brief_profile');

        $setting->setName('students.extended_brief_profile')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Extended Brief Profile')
            ->__set('description', 'The extended version of the brief student profile includes contact information of parents.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.student_agreement_options');

        $setting->setName('students.student_agreement_options')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Student Agreement Options')
            ->__set('description', 'List of agreements that students might be asked to sign in school (e.g. ICT Policy).');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', 'tutors,tutors_teachers')
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_student_settings';

        return $sections;
    }
}