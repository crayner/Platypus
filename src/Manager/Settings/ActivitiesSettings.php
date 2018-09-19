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
class ActivitiesSettings implements SettingCreationInterface
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
        $settings = [];

        $setting = $sm->createOneByName('activities.date_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('displayName', 'Date Type')
            ->__set('choice', ['date', 'term'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue('term')
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'Should activities be organised around dates (flexible) or terms (easy)?');
        if (empty($setting->getValue())) {
            $setting->setValue('term');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.max_per_term');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('integer')
            ->__set('choice', null)
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 5]),
                ]
            )
            ->setDefaultValue('0')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Maximum Activities per Term')
            ->__set('description', 'The most a student can sign up for in one term. Set to 0 for unlimited.');
        if (empty($setting->getValue())) {
            $setting->setValue('0');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.access');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['none', 'view', 'register'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue('register')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Access')
            ->__set('description', 'System-wide access control');
        if (empty($setting->getValue())) {
            $setting->setValue('register');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.payment');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['none', 'single', 'per_activity', 'single_per_activity'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue('per_activity')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Payment')
            ->__set('description', 'Payment system');
        if (empty($setting->getValue())) {
            $setting->setValue('per_activity');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.enrolment_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['competitive', 'selection'])
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue('competitive')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enrolment Type')
            ->__set('description', 'Enrolment process type');
        if (empty($setting->getValue())) {
            $setting->setValue('competitive');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.backup_choice');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Backup Choice')
            ->__set('description', 'Allow students to choose a backup, in case enroled activity is full.');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.activity_types');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Activity Types')
            ->__set('description', 'List of the different activity types available in school. Leave blank to disable this feature.');
        if (empty($setting->getValue())) {
            $setting->setValue(['creativity', 'action', 'service']);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.disable_external_provider_signup');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Disable External Provider Signup')
            ->__set('description', 'Should we turn off the option to sign up for activities provided by an outside agency?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.hide_external_provider_cost');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Hide External Provider Cost')
            ->__set('description', 'Should we hide the cost of activities provided by an outside agency from the Activities View?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Activity Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_activities_settings';

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
