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
 * Date: 22/06/2018
 * Time: 13:05
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FacilitySettings
 * @package App\Manager\Settings
 */
class FacilitySettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Facility';
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

        $setting = $sm->createOneByName('school_admin.facility_types')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDisplayName('Facility Types')
           ->setDescription('List of types for facilities.');
        if (empty($setting->getValue()))
            $setting->setValue(['Classroom','Hall','Laboratory','Library','Office','Outdoor','Performance','Staffroom','Storage','Study','Undercover','other']);
        $this->addSetting($setting, []);

        $this->addSection('Facility Settings');
        $this->setSectionsHeader('manage_facility_settings');

        $this->setSettingManager(null);

        return $this;
    }

}
