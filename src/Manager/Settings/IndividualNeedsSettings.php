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

/**
 * Class IndividualNeedsSettings
 * @package App\Manager\Settings
 */
class IndividualNeedsSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'IndividualNeeds';
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

        $setting = $sm->createOneByName('individual_needs.targets_template')
            ->setSettingType('html')
            ->setDisplayName('Targets Template')
            ->setDescription('An HTML template to be used in the targets field.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('individual_needs.teaching_strategies_template')
            ->setSettingType('html')
            ->setDisplayName('Teaching Strategies Template')
            ->setDescription('An HTML template to be used in the teaching strategies field.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('individual_needs.notes_review_template')
            ->setSettingType('html')
            ->setDisplayName('Notes & Review Template')
            ->setDescription('An HTML template to be used in the notes and review field.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection( 'Templates');

        $this->setSectionsHeader('individual_needs_descriptor_header');

        $this->setSettingManager(null);

        return $this;
    }
}
