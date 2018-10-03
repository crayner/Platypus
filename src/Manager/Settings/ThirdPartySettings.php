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
 * Date: 01/08/2018
 * Time: 13:36
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use Hillrange\Form\Validator\Integer;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class ThirdPartySettings
 * @package App\Manager\Settings
 */
class ThirdPartySettings extends SettingCreationManager
{
    use SMSSettings;

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ThirdParty';
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
        $setting = $sm->createOneByName('google.o_auth')
            ->setSettingType('boolean')
            ->setDisplayName('Google Integration')
            ->setDescription('Enable Gibbon-wide integration with the Google APIs?')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $setting = $sm->createOneByName('google.client_name')
            ->setSettingType('text')
            ->setDisplayName('Google Developers Client Name')
            ->setDescription('Name of Google Project in Developers Console.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $setting = $sm->createOneByName('google.client_id')
            ->setSettingType('text')
            ->setDisplayName('Google Developers Client ID')
            ->setDescription('Client ID for Google Project In Developers Console.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $setting = $sm->createOneByName('google.client_secret')
            ->setSettingType('text')
            ->setDisplayName('Google Developers Client Secret')
            ->setDescription('Client Secret for Google Project In Developers Console.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $route = $sm->getContainer()->get('router')->generate('connect_google_check', [], RouterInterface::ABSOLUTE_URL);

        $setting = $sm->createOneByName('google.redirect_uri')
            ->setSettingType('text')
            ->setDisplayName('Google Developers Redirect Uri')
            ->setDescription('Google Redirect on successful authentication. (Hint: This will be a page on your site.)')
            ->setValidators(null)
            ->setValue($route);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $setting = $sm->createOneByName('google.developer_key')
            ->setSettingType('text')
            ->setDisplayName('Google Developers Developer Key')
            ->setDescription('Google project Developer Key.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $setting = $sm->createOneByName('system.calendar_feed')
            ->setSettingType('text')
            ->setDisplayName('School Google Calendar ID')
            ->setDescription('Google Calendar ID for your school calendar. Only enables timetable integration when logging in via Google.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'google.o_auth']);

        $this->addSection('Google Integration', "If your school uses Google Apps, you can enable single sign on and calendar integration with Gibbon. This process makes use of Google's APIs, and allows a user to access Gibbon without a username and password, provided that their listed email address is a Google account to which they have access. For configuration instructions, <a href='https://gibbonedu.org/support/administrators/installing-gibbon/authenticating-with-google-oauth/' target='_blank'>click here</a>.");

        $setting = $sm->createOneByName('system.enable_payments')
            ->setSettingType('boolean')
            ->setDisplayName('PayPal API Integration')
            ->setDescription('')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, $arguments = ['hideParent' => 'system.enable_payments']);

        $setting = $sm->createOneByName('system.paypal_apiusername')
            ->setSettingType('string')
            ->setDisplayName('PayPal API Username')
            ->setDescription('API username provided by PayPal.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'system.enable_payments']);

        $setting = $sm->createOneByName('system.paypal_apipassword')
            ->setSettingType('string')
            ->setDisplayName('PayPal API Password')
            ->setDescription('API password provided by PayPal.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'system.enable_payments']);

        $setting = $sm->createOneByName('system.paypal_apisignature')
            ->setSettingType('text')
            ->setDisplayName('PayPal API Signature')
            ->setDescription('API signature provided by PayPal.')
            ->setValidators(null);
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, $arguments = ['hideParent' => 'system.enable_payments']);

        $this->addSection('PayPal Payment Gateway');

        $this->getSMSSettings();

        $setting = $sm->createOneByName('mailer_transport')
            ->setSettingType('enum')
            ->setDisplayName('EMail Transport')
            ->setDescription('')
            ->setFlatChoices(['','smtp','mail','sendmail','gmail'])
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransport'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_host')
            ->setSettingType('string')
            ->setDisplayName('EMail Host')
            ->setDescription('Only the domain name is required, so \'smtp.gmail.com\' or \'hotmail.com\'')
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_user')
            ->setSettingType('string')
            ->setDisplayName('EMail User Name')
            ->setDescription('Consult your email host for details of the username.')
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_password')
            ->setSettingType('string')
            ->setDisplayName('EMail Password')
            ->setDescription('')
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_port')
            ->setSettingType('string')
            ->setDisplayName('EMail Server Port')
            ->setDescription('The port that the server has open to receive outgoing emails. Standard port for email systems include 25, 465, 587, but you sever information should give this detail.')
            ->setValidators([
                new Integer(),
                new Range(['max' => 65535, 'min' => 1])
            ]);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_encryption')
            ->setSettingType('enum')
            ->setDisplayName('EMail Server Encryption')
            ->setDescription('Encryption details can be found in your email server documentation.')
            ->setChoices([
                'mailer.encryption.none' => 'none',
                'mailer.encryption.ssl'  => 'ssl',
                'mailer.encryption.tls'  => 'tls',
            ])
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true]);

        $setting = $sm->createOneByName('mailer_auth_mode')
            ->setSettingType('enum')
            ->setDisplayName('EMail Server Authority Mode')
            ->setDescription('Authority Mode details can be found in your email server documentation.')
            ->setChoices([
                'mailer.auth_mode.plain'    => 'plain',
                'mailer.auth_mode.login'    => 'login',
                'mailer.auth_mode.cram-md5' => 'cram-md5',
            ])
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true, 'default' => 'plain']);

        $setting = $sm->createOneByName('mailer_sender_name')
            ->setSettingType('string')
            ->setDisplayName('EMail Sender Name')
            ->setDescription('This is the name that will appear on all emails sent by the site. It can be a personal name, or the name of the school.')
            ->setValidators(null);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true, 'default' => 'plain']);

        $setting = $sm->createOneByName('mailer_sender_address')
            ->setSettingType('string')
            ->setDisplayName('EMail Sender Address')
            ->setDescription('This is the email address that will appear on all emails sent by the site. It will be the address that replies come too. If you use a none reply address, then each email message should clearly state that replies are not received by the school.')
            ->setValidators([
                new Email(),
            ]);
        $this->addSetting($setting, $arguments = ['formAttr' => ['class' => 'emailTransportHide'], 'parameter' => true, 'default' => $sm->get('org.email', 'email@some-domain.org')]);

        $this->addSection('EMail Settings', 'Email Settings can be found with your email provider. This system does have a number of preset to set default values. Gmail has been implemented by this site. This site uses <a href="https://swiftmailer.symfony.com/docs/introduction.html" target="_blank">Swiftmailer</a> as the engine to send email messages.');

        $this->setSectionsHeader('third_party_settings');
        $this->setSettingManager(null);

        return $this;
    }
}
