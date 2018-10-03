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
class StudentsSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('students.enable_student_notes')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Student Notes')
            ->setDescription('Should student notes be turned on?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.note_creation_notification')
            ->setSettingType('enum')
            ->setChoices([
                'students.note_creation_notification.tutors' => 'tutors',
                'students.note_creation_notification.tutors_teachers' => 'tutors_teachers'
            ])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDisplayName('Note Creation Notification')
            ->setDescription('Determines who to notify when a new student note is created.');
        if (empty($setting->getValue()))
            $setting->setValue('tutors');
        $this->addSetting($setting, []);

        $this->addSection('Student Notes');

        $setting = $sm->createOneByName('students.academic_alert_low_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Low Academic Alert Threshold')
            ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a low level academic alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(3);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.academic_alert_medium_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Medium Academic Alert Threshold')
            ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a medium level academic alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(5);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.academic_alert_high_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('High Academic Alert Threshold')
            ->setDescription('The number of Markbook concerns needed in the past 60 days to raise a high level academic alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(9);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.behaviour_alert_low_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Low Behaviour Alert Threshold')
            ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a low level Behaviour alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(3);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.behaviour_alert_medium_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('Medium Behaviour Alert Threshold')
            ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a medium level Behaviour alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(5);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.behaviour_alert_high_threshold')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 50])
                ]
            )
            ->setDisplayName('High Behaviour Alert Threshold')
            ->setDescription('The number of Behaviour concerns needed in the past 60 days to raise a high level Behaviour alert on a student.');
        if (empty($setting->getValue()))
            $setting->setValue(9);
        $this->addSetting($setting, []);

        $this->addSection( 'Alerts');

        $setting = $sm->createOneByName('students.extended_brief_profile')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Extended Brief Profile')
            ->setDescription('The extended version of the brief student profile includes contact information of parents.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('school_admin.student_agreement_options')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDisplayName('Student Agreement Options')
            ->setDescription('List of agreements that students might be asked to sign in school (e.g. ICT Policy).');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, []);

        $this->addSection('Miscellaneous');

        $this->setSectionsHeader('manage_student_settings');

        $this->setSettingManager(null);

        return $this;
    }
}
