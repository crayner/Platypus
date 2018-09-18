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
class FacilitySettings implements SettingCreationInterface
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
        $settings = [];

        $setting = $sm->createOneByName('school_admin.facility_types');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(
                [
                    new NotBlank(),
                ]
            )
            ->setDefaultValue(['classroom','hall','laboratory','library','office','outdoor','performance','staffroom','storage','study','undercover','other'])
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Facility Types')
            ->__set('description', 'List of types for facilities.');
        if (empty($setting->getValue())) {
            $setting->setValue(['classroom','hall','laboratory','library','office','outdoor','performance','staffroom','storage','study','undercover','other'])
            ;
        }
        $settings[] = $setting;
        $sections = [];

        $section['name'] = 'Facility Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_facility_settings';

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
