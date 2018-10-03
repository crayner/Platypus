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

use App\Manager\AttendanceCodeManager;
use App\Manager\SettingManager;
use App\Manager\StaffManager;
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
     * @param SettingManager $sm
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {
        $sections = [];
        $settings = [];

        $setting = $sm->createOneByName('attendance.attendance_reasons');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')

            ->setValidators([
                new NotBlank(),
                new Yaml(),
            ])
            ->setDefaultValue(['pending', 'education', 'family', 'medical', 'other'])
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Attendance Reasons')
           ->setDescription('List of reasons which are available when taking attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue(['pending', 'education', 'family', 'medical', 'other']);
        }
        $settings[] = $setting;

        $section['name'] = 'Reasons';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('attendance.prefill_roll_group');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Pre-Fill Roll Group Attendance')
           ->setDescription('Should Attendance by Roll Group be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.prefill_class');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Pre-Fill Class Attendance')
           ->setDescription('Should Attendance by Class be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.prefill_person');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Pre-Fill Person Attendance')
           ->setDescription('Should Attendance by Person be pre-filled with data available from other contexts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.default_roll_group_attendance_type');

        $setting->setName('attendance.default_roll_group_attendance_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', AttendanceCodeManager::getActiveAttendanceCodeList())
            ->setValidators(null)
            ->setDefaultValue('Present')
            ->setDisplayName('Default Roll Group Attendance Type')
           ->setDescription('The default selection for attendance type when taking Roll Group attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue('Present');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.default_class_attendance_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', AttendanceCodeManager::getActiveAttendanceCodeList())
            ->setValidators(null)
            ->setDefaultValue('Present')
            ->setDisplayName('Default Class Attendance Type')
           ->setDescription('The default selection for attendance type when taking Class attendance.');
        if (empty($setting->getValue())) {
            $setting->setValue('Present');
        }
        $settings[] = $setting;

        $section['name'] = 'Pre-Fills and Defaults';
        $section['description'] = 'The pre-fill settings below determine which Attendance contexts are preset by data available from other contexts. This allows, for example, for attendance taken in a class to be preset by attendance already taken in a Roll Group. The contexts for attendance include Roll Group, Class, Person, Future and Self Registration.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('attendance.student_self_registration_ipaddresses');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')

            ->setValidators(null)
            ->__set('translateChoice', 'School')
            ->setDisplayName('Student Self Registration IP Addresses')
           ->setDescription('List of IP addresses within which students can self register.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.self_registration_redirect');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'School')
            ->setDisplayName('Self Registration Redirect')
           ->setDescription('Should self registration redirect to Message Wall?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $section['name'] = 'Student Self Registration';
        $section['description'] = 'Your current IP address is (%{ip_address}).';
        $section['description_parameters'] = ['%{ip_address}' => $sm->getRequest()->getClientIp()];
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('attendance.attendance_clinotify_by_roll_group');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'School')
            ->setDisplayName('Enable Notifications by Roll Group')
           ->setDescription(null);
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.attendance_clinotify_by_class');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')

            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'School')
            ->setDisplayName('Enable Notifications by Class')
           ->setDescription(null);
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('attendance.attendance_cliadditional_users');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->setDisplayName('Additional Users to Notify')
            ->__set('choice', array_flip(StaffManager::getStaffListChoice()))
           ->setDescription('Send the school-wide daily attendance report to additional users. Restricted to roles with permission to access Roll Groups Not Registered or Classes Not Registered.<br/>Use Control, Command and/or Shift to select multiple');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $section['name'] = 'Attendance CLI';
        $section['description'] = '';
        $section['description_parameters'] = [];
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_attendance_settings';

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
