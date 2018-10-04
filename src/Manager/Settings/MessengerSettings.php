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
use Hillrange\Form\Validator\Colour;

/**
 * Class MessengerSettings
 * @package App\Manager\Settings
 */
class MessengerSettings extends SettingCreationManager
{
    use SMSSettings;
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
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {

        $this->setSettingManager($sm);
        self::getSMSSettings($sm);


        $setting = $sm->createOneByName('messenger.message_bubble_width_type')
            ->setSettingType('enum')
            ->setChoices([
                'messenger.message_bubble_width_type.regular' => 'regular',
                'messenger.message_bubble_width_type.wide' => 'wide',
            ])
            ->setDisplayName('Message Bubble Width Type')
            ->setDescription('Should the message bubble be regular or wide?');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('messenger.message_bubble_bgcolour')
            ->setSettingType('string')
            ->setValidators(
                [
                    new Colour(['enforceType' => 'rgba']),
                ]
            )
            ->setDisplayName('Message Bubble Background Colour')
            ->setDescription('Message bubble background colour in RGBA (e.g. 100,100,100,0.50). If blank, theme default will be used.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('messenger.message_bubble_auto_hide')
            ->setSettingType('boolean')
            ->setDisplayName('Message Bubble Auto Hide')
            ->setDescription('Should message bubble fade out automatically?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('messenger.enable_home_screen_widget')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Home Screen Widget')
            ->setDescription('Adds a Message Wall widget to the home page, highlighting current messages.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Message Wall Settings');

        $this->setSectionsHeader('manage_messenger_settings');

        $this->setSettingManager(null);

        return $this;
    }
}
