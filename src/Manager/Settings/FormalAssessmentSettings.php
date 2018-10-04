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
 * Time: 23:27
 */
namespace App\Manager\Settings;

use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentField;
use App\Entity\SchoolYear;
use App\Manager\ExternalAssessmentManager;
use App\Manager\SettingManager;
use App\Util\StringHelper;
use App\Validator\Yaml;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class FormalAssessmentSettings
 * @package App\Manager\Settings
 */
class FormalAssessmentSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'FormalAssessment';
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

        $setting = $sm->createOneByName('formal_assessment.internal_assessment_types')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Internal Assessment Types')
            ->setDescription('List of types to make available in Internal Assessments.');
        if (empty($setting->getValue()))
            $setting->setValue(['Expected Grade','Predicted Grade','Target Grade']);
        $this->addSetting($setting, []);


        $this->addSection('Primary External Assessment');
        $this->setSectionsHeader('manage_formal_assessments');

        $this->setSettingManager(null);

        return $this;
    }
}
