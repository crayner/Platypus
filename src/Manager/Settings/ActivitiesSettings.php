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
     * @return array
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('activities.date_type');

        $setting->setName('activities.date_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Date Type')
            ->__set('description', 'Should activities be organised around dates (flexible) or terms (easy)?');
        if (empty($setting->getValue())) {
            $setting->setValue('term')
                ->__set('choice', ['date','term'])
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue('term')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.max_per_term');

        $setting->setName('activities.max_per_term')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('integer')
            ->__set('displayName', 'Maximum Activities per Term')
            ->__set('description', 'The most a student can sign up for in one term. Set to 0 for unlimited.');
        if (empty($setting->getValue())) {
            $setting->setValue('0')
                ->__set('choice', null)
                ->setValidators(
                    [
                        new Range(['min' => 0, 'max' => 5]),
                    ]
                )
                ->setDefaultValue('0')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.access');

        $setting->setName('activities.access')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Access')
            ->__set('description', 'System-wide access control');
        if (empty($setting->getValue())) {
            $setting->setValue('register')
                ->__set('choice', ['none','view','register'])
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue('register')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.payment');

        $setting->setName('activities.payment')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice', null)
            ->__set('displayName', 'Payment')
            ->__set('description', 'Payment system');
        if (empty($setting->getValue())) {
            $setting->setValue('per_activity')
                ->__set('choice', ['none, single, per_activity, single_per_activity'])
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue('per_activity')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.enrolment_type');

        $setting->setName('activities.enrolment_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice', null)
            ->__set('displayName', 'Enrolment Type')
            ->__set('description', 'Enrolment process type');
        if (empty($setting->getValue())) {
            $setting->setValue('competitive')
                ->__set('choice', ['competitive, selection'])
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue('competitive')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.backup_choice');

        $setting->setName('activities.backup_choice')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Backup Choice')
            ->__set('description', 'Allow students to choose a backup, in case enroled activity is full.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.activity_types');

        $setting->setName('activities.activity_types')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Activity Types')
            ->__set('description', 'List of the different activity types available in school. Leave blank to disable this feature.');
        if (empty($setting->getValue())) {
            $setting->setValue(['creativity','action','service'])
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.disable_external_provider_signup');

        $setting->setName('activities.disable_external_provider_signup')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Disable External Provider Signup')
            ->__set('description', 'Should we turn off the option to sign up for activities provided by an outside agency?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('activities.hide_external_provider_cost');

        $setting->setName('activities.hide_external_provider_cost')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Hide External Provider Cost')
            ->__set('description', 'Should we hide the cost of activities provided by an outside agency from the Activities View?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Activity Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_activities_settings';

        return $sections;
    }
}
