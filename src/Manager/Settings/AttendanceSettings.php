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
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AttendanceSettings
 * @package App\Manager\Settings
 */
class AttendanceSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('attendance.attendance_reasons')
            ->setSettingType('array')
            ->setValidators([
                new NotBlank(),
                new Yaml(),
            ])
            ->setDisplayName('Attendance Reasons')
            ->setDescription('List of reasons which are available when taking attendance.');
        if (empty($setting->getValue()))
            $setting->setValue(['pending', 'education', 'family', 'medical', 'other']);
        $this->addSetting($setting, []);

        $this->addSection('Reasons');

        $setting = $sm->createOneByName('attendance.prefill_roll_group')
            ->setSettingType('boolean')
            ->setDisplayName('Pre-Fill Roll Group Attendance')
            ->setDescription('Should Attendance by Roll Group be pre-filled with data available from other contexts?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.prefill_class')
            ->setSettingType('boolean')
            ->setDisplayName('Pre-Fill Class Attendance')
            ->setDescription('Should Attendance by Class be pre-filled with data available from other contexts?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.prefill_person')
            ->setSettingType('boolean')
            ->setDisplayName('Pre-Fill Person Attendance')
            ->setDescription('Should Attendance by Person be pre-filled with data available from other contexts?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.default_roll_group_attendance_type')
            ->setSettingType('enum')
            ->setChoices(AttendanceCodeManager::getActiveAttendanceCodeList())
            ->setDisplayName('Default Roll Group Attendance Type')
             ->setDescription('The default selection for attendance type when taking Roll Group attendance.');
        if (empty($setting->getValue()))
            $setting->setValue('Present');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.default_class_attendance_type')
            ->setSettingType('enum')
            ->setChoices(AttendanceCodeManager::getActiveAttendanceCodeList())
            ->setDisplayName('Default Class Attendance Type')
            ->setDescription('The default selection for attendance type when taking Class attendance.');
        if (empty($setting->getValue()))
            $setting->setValue('Present');
        $this->addSetting($setting, []);

        $this->addSection('Pre-Fills and Defaults', 'The pre-fill settings below determine which Attendance contexts are preset by data available from other contexts. This allows, for example, for attendance taken in a class to be preset by attendance already taken in a Roll Group. The contexts for attendance include Roll Group, Class, Person, Future and Self Registration.');

        $setting = $sm->createOneByName('attendance.student_self_registration_ipaddresses')
            ->setSettingType('array')
            ->setDisplayName('Student Self Registration IP Addresses')
            ->setDescription('List of IP addresses within which students can self register.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.self_registration_redirect')
            ->setSettingType('boolean')
            ->setDisplayName('Self Registration Redirect')
            ->setDescription('Should self registration redirect to Message Wall?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Student Self Registration', 'Your current IP address is (%{ip_address}).', ['description_parameters' => ['%{ip_address}' => $sm->getRequest()->getClientIp()]]);


        $setting = $sm->createOneByName('attendance.attendance_clinotify_by_roll_group')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Notifications by Roll Group')
            ->setDescription(null);
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.attendance_clinotify_by_class')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Notifications by Class')
            ->setDescription(null);
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('attendance.attendance_cliadditional_users')
            ->setSettingType('multiEnum')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setDescription('Send the school-wide daily attendance report to additional users. Restricted to roles with permission to access Roll Groups Not Registered or Classes Not Registered.<br/>Use Control, Command and/or Shift to select multiple');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, []);

        $this->addSection('Attendance CLI');

        $this->setSectionsHeader('manage_attendance_settings');

        $this->setSettingManager(null);

        return $this;
    }

    /**
     * @var Collection
     */
    private $attendanceCodes;

    /**
     * getAttendanceCodes
     *
     * @return null|Collection
     */
    public function getAttendanceCodes(): ?Collection
    {
        return $this->attendanceCodes;
    }

    /**
     * setAttendanceCodes
     *
     * @param Collection $attendanceCodes
     * @return AttendanceSettings
     */
    public function setAttendanceCodes(Collection $attendanceCodes): AttendanceSettings
    {
        $this->attendanceCodes = $attendanceCodes;
        return $this;
    }
}
