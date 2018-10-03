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
 * Date: 29/09/2018
 * Time: 15:40
 */

namespace App\Manager\Settings;


trait SMSSettings
{

    /**
     * getSMSSettings
     *
     */
    public function getSMSSettings(): void
    {
        $sm = $this->getSettingManager();

        $setting = $sm->createOneByName('messenger.enable_sms')
            ->setSettingType('boolean')
            ->setDisplayName('Enable SMS Settings')
            ->setDescription('')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'messenger.enable_sms']);


        $setting = $sm->createOneByName('messenger.sms_username')
            ->setSettingType('string')
            ->setValidators(null)
            ->setDisplayName('SMS Username ')
            ->setDescription('SMS gateway username.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'messenger.enable_sms']);

        $setting = $sm->createOneByName('messenger.sms_password')
            ->setSettingType('string')
            ->setValidators(null)
            ->setDisplayName('SMS Password ')
            ->setDescription('SMS gateway password.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'messenger.enable_sms']);

        $setting = $sm->createOneByName('messenger.sms_url')
            ->setSettingType('url')
            ->setValidators(null)
            ->setDisplayName('SMS URL')
            ->setDescription('SMS gateway URL for send requests.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'messenger.enable_sms']);

        $setting = $sm->createOneByName('messenger.sms_urlcredit')
            ->setSettingType('url')
            ->setValidators(null)
            ->setDisplayName('SMS URL Credit')
            ->setDescription('SMS gateway URL for checking credit.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'messenger.enable_sms']);

        $this->addSection('SMS Settings', 'Busybee is designed to use the <a href="http://onewaysms.com" target="_blank">One Way SMS</a> gateway to send out SMS messages. This is a paid service, not affiliated with Gibbon, and you must create your own account with them before being able to send out SMSs using the Messenger module. It is possible that completing the fields below with details from other gateways may work.');
    }
}