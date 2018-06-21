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
 * Date: 20/06/2018
 * Time: 11:28
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Validator\Yaml;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AttendanceSettings
 * @todo Staff List
 * @package App\Manager\Settings
 */
class AttendanceSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Attendance';
    }

    /**
     * getSettings
     *
     * @return array
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('attendance.attendance_reasons');

        $setting->setName('attendance.attendance_reasons')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Attendance Reasons')
            ->__set('description', 'List of reasons which are available when taking attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue(['pending', 'education', 'family', 'medical', 'other'])
                ->__set('choice', null)
                ->setValidators([
                    new NotBlank(),
                    new Yaml(),
                ])
                ->setDefaultValue(['pending', 'education', 'family', 'medical', 'other'])
                ->__set('translateChoice', 'Setting');
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Reasons';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('attendance.prefill_roll_group');

        $setting->setName('attendance.prefill_roll_group')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Pre-Fill Roll Group Attendance')
            ->__set('description', 'Should Attendance by Roll Group be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.prefill_class');

        $setting->setName('attendance.prefill_class')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Pre-Fill Class Attendance')
            ->__set('description', 'Should Attendance by Class be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.prefill_person');

        $setting->setName('attendance.prefill_person')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Pre-Fill Person Attendance')
            ->__set('description', 'Should Attendance by Person be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.default_roll_group_attendance_type');

        $setting->setName('attendance.default_roll_group_attendance_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Default Roll Group Attendance Type')
            ->__set('description', 'The default selection for attendance type when taking Roll Group attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue('Present')
                ->__set('choice', 'App\Manager\AttendanceCodeManager::getActiveAttendanceCodeList')
                ->setValidators(null)
                ->setDefaultValue('Present')
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.default_class_attendance_type');

        $setting->setName('attendance.default_class_attendance_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Default Class Attendance Type')
            ->__set('description', 'The default selection for attendance type when taking Class attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue('Present')
                ->__set('choice', 'App\Manager\AttendanceCodeManager::getActiveAttendanceCodeList')
                ->setValidators(null)
                ->setDefaultValue('Present')
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $section['name'] = 'Pre-Fills and Defaults';
        $section['description'] = 'The pre-fill settings below determine which Attendance contexts are preset by data available from other contexts. This allows, for example, for attendance taken in a class to be preset by attendance already taken in a Roll Group. The contexts for attendance include Roll Group, Class, Person, Future and Self Registration.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('attendance.student_self_registration_IP_addresses');

        $setting->setName('attendance.student_self_registration_IP_addresses')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Student Self Registration IP Addresses')
            ->__set('description', 'List of IP addresses within which students can self register.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.self_registration_redirect');

        $setting->setName('attendance.self_registration_redirect')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Self Registration Redirect')
            ->__set('description', 'Should self registration redirect to Message Wall?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $section['name'] = 'Student Self Registration';
        $section['description'] = 'Your current IP address is (%{ip_address}).';
        $section['description_parameters'] = ['%{ip_address}' => $sm->getRequest()->getClientIp()];
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('attendance.attendance_CLI_notify_by_roll_group');

        $setting->setName('attendance.attendance_CLI_notify_by_roll_group')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Notifications by Roll Group')
            ->__set('description', null);
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.attendance_CLI_notify_by_class');

        $setting->setName('attendance.attendance_CLI_notify_by_class')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Notifications by Class')
            ->__set('description', null);
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.attendance_CLI_additional_users');

        $setting->setName('attendance.attendance_CLI_additional_users')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Additional Users to Notify')
            ->__set('description', 'Send the school-wide daily attendance report to additional users. Restricted to roles with permission to access Roll Groups Not Registered or Classes Not Registered.<br/>Use Control, Command and/or Shift to select multiple');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', 'do this list..,  Needs Staff table defined.')
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $section['name'] = 'Attendance CLI';
        $section['description'] = '';
        $section['description_parameters'] = [];
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_attendance_settings';

        return $sections;
    }
}