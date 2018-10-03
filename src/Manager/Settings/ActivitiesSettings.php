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
 * Class ActivitiesSettings
 * @package App\Manager\Settings
 */
class ActivitiesSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Activities';
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
        $setting = $sm->createOneByName('activities.date_type')
            ->setSettingType('enum')
            ->setDisplayName('Date Type')
            ->setChoices([
                'activities.date_type.term' => 'term',
                'activities.date_type.date' => 'date'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDescription('Should activities be organised around dates (flexible) or terms (easy)?');
        if (empty($setting->getValue()))
            $setting->setValue('term');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.max_per_term')
            ->setSettingType('enum')
            ->setChoices([0,1,2,3,4,5])
            ->setDisplayName('Maximum Activities per Term')
            ->setDescription('The most a student can sign up for in one term. Set to 0 for unlimited.');
        if (empty($setting->getValue()))
            $setting->setValue('0');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.access')
            ->setSettingType('enum')
            ->setChoices([
                'activities.access.none' => 'none',
                'activities.access.view' => 'view',
                'activities.access.register' => 'register'
            ])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDisplayName('Access')
            ->setDescription('System-wide access control');
        if (empty($setting->getValue()))
            $setting->setValue('register');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.payment')
            ->setSettingType('enum')
            ->setChoices([
                'activities.payment.none' => 'none',
                'activities.payment.single' => 'single',
                'activities.payment.per_activity' => 'per_activity',
                'activities.payment.single_per_activity' => 'single_per_activity'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDisplayName('Payment')
            ->setDescription('Payment system');
        if (empty($setting->getValue()))
            $setting->setValue('per_activity');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.enrolment_type')
            ->setSettingType('enum')
            ->setChoices([
                'activities.enrolment_type.competitive' => 'competitive',
                'activities.enrolment_type.selection' => 'selection'
            ])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDisplayName('Enrolment Type')
            ->setDescription('Enrolment process type');
        if (empty($setting->getValue()))
            $setting->setValue('competitive');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.backup_choice')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Backup Choice')
            ->setDescription('Allow students to choose a backup, in case enroled activity is full.');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.activity_types')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDisplayName('Activity Types')
            ->setDescription('List of the different activity types available in school. Leave blank to disable this feature.');
        if (empty($setting->getValue()))
            $setting->setValue(['creativity', 'action', 'service']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.disable_external_provider_signup')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Disable External Provider Signup')
            ->setDescription('Should we turn off the option to sign up for activities provided by an outside agency?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('activities.hide_external_provider_cost')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDisplayName('Hide External Provider Cost')
            ->setDescription('Should we hide the cost of activities provided by an outside agency from the Activities View?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Activity Settings');

        $this->setSectionsHeader('manage_activities_settings');

        $this->setSettingManager(null);

        return $this;
    }
}
