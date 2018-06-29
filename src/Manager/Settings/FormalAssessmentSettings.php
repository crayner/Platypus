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
class FormalAssessmentSettings implements SettingCreationInterface
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
        $settings = [];

        $setting = $sm->createOneByName('formal_assessment.internal_assessment_types');

        $setting->setName('formal_assessment.internal_assessment_types')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Internal Assessment Types')
            ->__set('description', 'List of types to make available in Internal Assessments.');
        if (empty($setting->getValue())) {
            $setting->setValue(['expected_grade','predicted_grade','target_grade'])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(['expected_grade','predicted_grade','target_grade'])
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;
        $sections = [];


        $section['name'] = 'Primary External Assessment';
        $section['description'] = '';
        $section['settings'] = $settings;
        $sections[] = $section;
        $sections['header'] = 'manage_formal_assessments';

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
