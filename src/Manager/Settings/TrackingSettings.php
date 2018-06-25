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
 * Date: 25/06/2018
 * Time: 08:01
 */
namespace App\Manager\Settings;

use App\Entity\ExternalAssessmentField;
use App\Manager\SettingManager;
use App\Util\StringHelper;
use App\Util\YearGroupHelper;
use App\Validator\Yaml;

/**
 * Class TrackingSettings
 * @package App\Manager\Settings
 */
class TrackingSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Tracking';
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

        new YearGroupHelper($sm->getEntityManager());

        $eaList = $sm->getEntityManager()->createQuery('SELECT DISTINCT a.id, a.nameShort, f.category FROM '.ExternalAssessmentField::class.' f JOIN f.externalAssessment a WHERE a.active = :active ORDER BY a.nameShort ASC, f.category ASC')
                ->setParameter('active', true)
                ->getArrayResult()
        ;

        foreach($eaList as $ea)
        {
            $catName = mb_substr($ea['category'], mb_strpos($ea['category'],'_') +1);
            $setting = $sm->createOneByName(strtolower('tracking.ext_ass_data_point.'.StringHelper::safeString($ea['nameShort']).'.'.StringHelper::safeString($catName)));

            $setting->setName(strtolower('tracking.ext_ass_data_point.'.StringHelper::safeString($ea['nameShort']).'.'.StringHelper::safeString($catName)))
                ->__set('role', 'ROLE_PRINCIPAL')
                ->setType('multiChoice')
                ->__set('displayName', $ea['nameShort'] . ' - ' . $catName)
                ->__set('description', 'Tracking External Assessment');
            if (empty($setting->getValue())) {
                $setting->setValue([])
                    ->__set('choice', YearGroupHelper::getYearGroupList())
                    ->setValidators(null)
                    ->setDefaultValue([])
                    ->__set('translateChoice', 'School')
                ;
            }
            $settings[] = $setting;
        }
        $sections = [];

        $section['name'] = 'Data Points - External Assessment';
        $section['description'] = 'Use the options below to select the external assessments that you wish to include in your Data Points export. If duplicates of any assessment exist, only the most recent entry will be shown.';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_tracking_settings';

        return $sections;
    }
}
