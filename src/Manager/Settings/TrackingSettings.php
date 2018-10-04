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

/**
 * Class TrackingSettings
 * @package App\Manager\Settings
 */
class TrackingSettings extends SettingCreationManager
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
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {
        $this->setSettingManager($sm);

        new YearGroupHelper($sm->getEntityManager());

        $eaList = $sm->getEntityManager()->createQuery('SELECT DISTINCT a.id, a.nameShort, c.category FROM '.ExternalAssessmentField::class.' f JOIN f.externalAssessment a JOIN f.externalAssessmentCategory c WHERE a.active = :active ORDER BY a.nameShort ASC, c.sequence')
                ->setParameter('active', true)
                ->getArrayResult()
        ;

        foreach($eaList as $ea)
        {
            $catName = $ea['category'];
            $setting = $sm->createOneByName(strtolower('tracking.ext_ass_data_point.'.StringHelper::safeString($ea['nameShort']).'.'.StringHelper::safeString($catName)))
                ->setSettingType('multiEnum')
                ->setChoices(YearGroupHelper::getYearGroupList())
                ->setDisplayName($ea['nameShort'] . ' - ' . $catName)
                ->setDescription('Tracking External Assessment');
            if (empty($setting->getValue()))
                $setting->setValue([]);
            $this->addSetting($setting, []);
        }
        $this->addSection('Data Points - External Assessment', 'Use the options below to select the external assessments that you wish to include in your Data Points export. If duplicates of any assessment exist, only the most recent entry will be shown.');
        $this->setSectionsHeader('manage_tracking_settings');

        foreach($sm->get('formal_assessment.internal_assessment_types', ['expected_grade','predicted_grade','target_grade']) as $ia)
        {
            $setting = $sm->createOneByName(strtolower('school_admin.external_assessments_by_year_group.'.StringHelper::safeString($ia)))
                ->setSettingType('multiEnum')
                ->setChoices(YearGroupHelper::getYearGroupList())->setDisplayName(strtolower('school_admin.external_assessments_by_year_group.'.StringHelper::safeString($ia)))
                ->setDescription('');
            if (empty($setting->getValue()))
                $setting->setValue([]);
            $this->addSetting($setting, []);
        }

        $this->addSection('Data Points - Internal Assessment', 'Use the options below to select the internal assessments that you wish to include in your Data Points export. If duplicates of any assessment exist, only the most recent entry will be shown.');
        $this->setSectionsHeader('manage_tracking_settings');

        $this->setSettingManager(null);
        
        return $this;
    }
}
