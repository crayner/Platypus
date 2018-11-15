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
use App\Validator\BackgroundImage;
use Hillrange\Form\Validator\Colour;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class LibrarySettings
 * @package App\Manager\Settings
 */
class LibrarySettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Library';
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
        $setting = $sm->createOneByName('library.default_loan_length')
            ->setSettingType('integer')
            ->setValidators(
                [
                    new Range(['min' => 0, 'max' => 31]),
                ]
            )
            ->setDisplayName('Default Loan Length')
            ->setDescription('The standard loan length for a library item, in days');
        if (empty($setting->getValue()))
            $setting->setValue('7');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('library.browse_bgcolour')
            ->setSettingType('colour')
            ->setDisplayName('Browse Library BG Colour ')
            ->setValidators(
                [
                    new NotBlank(),
                    new Colour(),
                ]
            )
            ->setDescription('Background colour used behind library browsing screen.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('library.browse_bgimage')
            ->setSettingType('image')
            ->setDisplayName('Browse Library BG Image')
            ->setValidators(
                [
                    new BackgroundImage(),
                ]
            )
            ->setDescription('URL to background image used behind library browsing screen.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Descriptors');

        $this->setSectionsHeader('Library Settings');

        $this->setSettingManager(null);

        return $this;
    }
}
