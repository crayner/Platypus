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
use App\Validator\Yaml;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ResourcesSettings
 * @package App\Manager\Settings
 */
class ResourcesSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Resources';
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

        $setting = $sm->createOneByName('resources.categories');

        $setting->setName('resources.categories')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Categories')
            ->__set('description', 'Allowable choices for category.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Article', 'Book', 'Document', 'Graphic', 'Idea', 'Music', 'Object', 'Painting', 'Person', 'Photo', 'Place', 'Poetry', 'Prose', 'Rubric', 'Text', 'Video', 'Website', 'Work Sample', 'Other'])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('resources.purposes_general');

        $setting->setName('resources.purposes_general')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Purposes (General)')
            ->__set('description', 'Allowable choices for purpose when creating a resource.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Assessment Aid', 'Concept', 'Inspiration', 'Learner Profile', 'Mass Mailer Attachment', 'Provocation', 'Skill', 'Teaching and Learning Strategy', 'Other'])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('resources.purposes_restricted');

        $setting->setName('resources.purposes_restricted')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Purposes (Restricted) ')
            ->__set('description', 'Additional allowable choices for purpose when creating a resource, for those with "Manage All Resources" rights.');
        if (empty($setting->getValue())) {
            $setting->setValue([])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Resource Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_resource_settings';

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
