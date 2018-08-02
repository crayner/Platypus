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
use App\Validator\Google;
use App\Validator\PayPal;
use App\Validator\SMS;
use App\Validator\Yaml;
use Hillrange\Form\Validator\Integer;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class ThirdPartySettings
 * @package App\Manager\Settings
 */
class ThirdPartySettings implements SettingCreationInterface
{
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
        $settings = [];
        $sections = [];

        $setting = $sm->createOneByName('google');

        $setting->setName('google')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('array')
            ->__set('displayName', 'Google Integration')
            ->__set('description', 'Enable Gibbon-wide integration with the Google APIs?')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(['o_auth' => '0',
                'client_id' => '',
                'client_secret' => '',])
            ->setValidators([
                new Yaml(),
                new Google(),
            ]);
        if (empty($setting->getValue())) {
            $setting->setValue(['o_auth' => '0',
                'client_id' => '',
                'client_secret' => '',]);
        }
        $settings[] = $setting;

        $section['name'] = 'Google Integration';
        $section['description'] = "If your school uses Google Apps, you can enable single sign on and calendar integration with Gibbon. This process makes use of Google's APIs, and allows a user to access Gibbon without a username and password, provided that their listed email address is a Google account to which they have access. For configuration instructions, <a href='https://gibbonedu.org/support/administrators/installing-gibbon/authenticating-with-google-oauth/' target='_blank'>click here</a>.";
        $section['settings'] = $settings;
        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('pay_pal');

        $setting->setName('pay_pal')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('array')
            ->__set('displayName', 'PayPal API Integration')
            ->__set('description', '')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(['enable' => '0',
                'username' => '',
                'password' => '',
                'signature' => '',])
            ->setValidators([
                new Yaml(),
                new PayPal(),
            ]);
        if (empty($setting->getValue())) {
            $setting->setValue(['enable' => '0',
                'username' => '',
                'password' => '',
                'signature' => '',]);
        }
        $settings[] = $setting;

        $section['name'] = 'PayPal Payment Gateway';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('sms');

        $setting->setName('sms')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('array')
            ->__set('displayName', 'SMS Settings')
            ->__set('description', '')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(['enable' => '0',
                'username' => '',
                'password' => '',
                'url' => '',
                'urlCredit' => '',
            ])
            ->setValidators([
                new Yaml(),
                new SMS(),
            ]);
        if (empty($setting->getValue())) {
            $setting->setValue(['enable' => '0',
                'username' => '',
                'password' => '',
                'url' => '',
                'urlCredit' => '',
            ]);
        }
        $settings[] = $setting;

        $section['name'] = 'SMS Settings';
        $section['description'] = 'Busybee is designed to use the <a href=\'http://onewaysms.com\' target=\'_blank\'>One Way SMS</a> gateway to send out SMS messages. This is a paid service, not affiliated with Gibbon, and you must create your own account with them before being able to send out SMSs using the Messenger module. It is possible that completing the fields below with details from other gateways may work.';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('mailer_transport');

        $setting->setName('mailer_transport')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'EMail Transport')
            ->__set('description', '')
            ->__set('choice', ['','smtp','mail','sendmail','gmail'])
            ->__set('translateChoice', 'Setting')
            ->setDefaultValue(null)
            ->setValidators(null)
            ->setParameter(true, $sm, null);
        $setting->setFormAttr(['class' => 'emailTransport']);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_host');

        $setting->setName('mailer_host')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail Host')
            ->__set('description', 'Only the domain name is required, so \'smtp.gmail.com\' or \'hotmail.com\'')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(null)
            ->setValidators(null)
            ->setFormAttr(['class' => 'emailTransportHide'])
            ->setParameter(true, $sm, null);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_user');

        $setting->setName('mailer_user')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail User Name')
            ->__set('description', 'Consult your email host for details of the username.')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(null)
            ->setValidators(null)
            ->setFormAttr(['class' => 'emailTransportHide'])
            ->setParameter(true, $sm, null);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_password');

        $setting->setName('mailer_password')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail Password')
            ->__set('description', '')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(null)
            ->setValidators(null)
            ->setFormAttr(['class' => 'emailTransportHide'])
            ->setParameter(true, $sm, null);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_port');

        $setting->setName('mailer_port')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail Server Port')
            ->__set('description', 'The port that the server has open to receive outgoing emails. Standard port for email systems include 25, 465, 587, but you sever information should give this detail.')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(465)
            ->setValidators([
                new Integer(),
                new Range(['max' => 65535, 'min' => 1])
            ])
            ->setParameter(true, $sm, 465)
            ->setFormAttr(['class' => 'emailTransportHide']);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_encryption');

        $setting->setName('mailer_encryption')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'EMail Server Encryption')
            ->__set('description', 'Encryption details can be found in your email server documentation.')
            ->__set('choice', [
                'mailer.encryption.none' => 'none',
                'mailer.encryption.ssl'  => 'ssl',
                'mailer.encryption.tls'  => 'tls',
            ])
            ->__set('translateChoice', 'Setting')
            ->setDefaultValue('none')
            ->setValidators(null)
            ->setParameter(true, $sm, 'none')
            ->setFormAttr(['class' => 'emailTransportHide']);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_auth_mode');

        $setting->setName('mailer_auth_mode')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'EMail Server Authority Mode')
            ->__set('description', 'Authority Mode details can be found in your email server documentation.')
            ->__set('choice', [
                'mailer.auth_mode.plain'    => 'plain',
                'mailer.auth_mode.login'    => 'login',
                'mailer.auth_mode.cram-md5' => 'cram-md5',
            ])
            ->__set('translateChoice', 'Setting')
            ->setDefaultValue('plain')
            ->setValidators(null)
            ->setParameter(true, $sm, 'plain')
            ->setFormAttr(['class' => 'emailTransportHide']);

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_sender_name');

        $setting->setName('mailer_sender_name')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail Sender Name')
            ->__set('description', 'This is the name that will appear on all emails sent by the site. It can be a personal name, or the name of the school.')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue('plain')
            ->setValidators(null)
            ->setFormAttr(['class' => 'emailTransportHide'])
            ->setParameter(true, $sm, 'Busybee Web Site');

        $settings[] = $setting;

        $setting = $sm->createOneByName('mailer_sender_address');

        $setting->setName('mailer_sender_address')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('string')
            ->__set('displayName', 'EMail Sender Address')
            ->__set('description', 'This is the email address that will appear on all emails sent by the site. It will be the address that replies come too. If you use a none reply address, then each email message should clearly state that replies are not received by the school.')
            ->__set('choice', null)
            ->__set('translateChoice', null)
            ->setDefaultValue(null)
            ->setValidators([
                new Email(),
            ])
            ->setFormAttr(['class' => 'emailTransportHide'])
            ->setParameter(true, $sm, $sm->get('org.email', 'email@some-domain.org'));

        $settings[] = $setting;

        $section['name'] = 'EMail Settings';
        $section['description'] = 'Email Settings can be found with your email provider. This system does have a number of preset to set default values. Gmail has been implemented by this site. This site uses <a href="https://swiftmailer.symfony.com/docs/introduction.html" target="_blank">Swiftmailer</a> as the engine to send email messages.';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'third_party_settings';

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
