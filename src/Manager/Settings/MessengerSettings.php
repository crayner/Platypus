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
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {

        $sections = [];
        $sections[] = self::getSMSSettings($sm);
        $settings = [];

        $setting = $sm->createOneByName('messenger.message_bubble_width_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['regular', 'wide'])
            ->setValidators(null)
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Message Bubble Width Type')
           ->setDescription('Should the message bubble be regular or wide?');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.message_bubble_bgcolour');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')

            ->setValidators(
                [
                    new Colour(['enforceType' => 'rgba']),
                ]
            )
            ->__set('translateChoice', 'Setting')
            ->setDisplayName('Message Bubble Background Colour')
           ->setDescription('Message bubble background colour in RGBA (e.g. 100,100,100,0.50). If blank, theme default will be used.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.message_bubble_auto_hide');

        $setting->setName('messenger.message_bubble_auto_hide')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->setDisplayName('Message Bubble Auto Hide')
           ->setDescription('Should message bubble fade out automatically?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)

                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('messenger.enable_home_screen_widget');

        $setting->setName('messenger.enable_home_screen_widget')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Home Screen Widget')
           ->setDescription('Adds a Message Wall widget to the home page, highlighting current messages.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)

                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Message Wall Settings';
        $section['description'] = '';
        $section['settings'] = $settings;
        $section['colour'] = 'warning';

        $sections[] = $section;

        $sections['header'] = 'manage_messenger_settings';

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
