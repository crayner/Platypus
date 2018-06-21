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
 * Date: 21/06/2018
 * Time: 14:55
 */

namespace App\Manager\Settings;
use App\Manager\SettingManager;
use Hillrange\Form\Validator\AlwaysInValid;
use Hillrange\Form\Validator\Colour;

/**
 * Class MessengerSettings
 * @package App\Manager\Settings
 */
class MessengerSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Messenger';
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

        $setting = $sm->createOneByName('messenger.sms_user_name');

        $setting->setName('messenger.sms_user_name')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('string')
            ->__set('displayName', 'SMS Username ')
            ->__set('description', 'SMS gateway username.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.sms_password');

        $setting->setName('messenger.sms_password')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('string')
            ->__set('displayName', 'SMS Password ')
            ->__set('description', 'SMS gateway password.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.sms_url');

        $setting->setName('messenger.sms_url')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('url')
            ->__set('displayName', 'SMS URL')
            ->__set('description', 'SMS gateway URL for send requests.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.sms_url_credit');

        $setting->setName('messenger.sms_url_credit')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('url')
            ->__set('displayName', 'SMS URL Credit')
            ->__set('description', 'SMS gateway URL for checking credit.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'SMS Settings';
        $section['description'] = 'Gibbon is designed to use the <a href="http://onewaysms.com" target="_blank">One Way SMS</a> gateway to send out SMS messages. This is a paid service, not affiliated with Gibbon, and you must create your own account with them before being able to send out SMSs using the Messenger module. It is possible that completing the fields below with details from other gateways may work.';
        $section['settings'] = $settings;
        $section['colour'] = 'info';

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('messenger.message_bubble_width_type');

        $setting->setName('messenger.message_bubble_width_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Message Bubble Width Type')
            ->__set('description', 'Should the message bubble be regular or wide?');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', 'regular, wide')
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.message_bubble_BG_color');

        $setting->setName('messenger.message_bubble_BG_color')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('string')
            ->__set('displayName', 'Message Bubble Background Colour')
            ->__set('description', 'Message bubble background colour in RGBA (e.g. 100,100,100,0.50). If blank, theme default will be used.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new Colour(['enforceType' => 'rgba']),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.message_bubble_auto_hide');

        $setting->setName('messenger.message_bubble_auto_hide')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Message Bubble Auto Hide')
            ->__set('description', 'Should message bubble fade out automatically?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.enable_home_screen_widget');

        $setting->setName('messenger.enable_home_screen_widget')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Home Screen Widget')
            ->__set('description', 'Adds a Message Wall widget to the home page, highlighting current messages.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Message Wall Settings';
        $section['description'] = '';
        $section['settings'] = $settings;
        $section['colour'] = 'info';

        $sections[] = $section;

        $sections['header'] = 'manage_messenger_settings';

        return $sections;
    }
}
