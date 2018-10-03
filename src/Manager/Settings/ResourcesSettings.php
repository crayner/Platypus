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
class ResourcesSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);
        $setting = $sm->createOneByName('resources.categories')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Categories')
            ->setDescription('Allowable choices for category.');
        if (empty($setting->getValue()))
            $setting->setValue(['Article', 'Book', 'Document', 'Graphic', 'Idea', 'Music', 'Object', 'Painting', 'Person', 'Photo', 'Place', 'Poetry', 'Prose', 'Rubric', 'Text', 'Video', 'Website', 'Work Sample', 'Other']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('resources.purposes_general')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Purposes (General)')
            ->setDescription('Allowable choices for purpose when creating a resource.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Assessment Aid', 'Concept', 'Inspiration', 'Learner Profile', 'Mass Mailer Attachment', 'Provocation', 'Skill', 'Teaching and Learning Strategy', 'Other'])

            ;
        }
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('resources.purposes_restricted')
            ->setSettingType('array')
            ->setValidators(
                [
                    new Yaml(),
                ]
            )

            ->setDisplayName('Purposes (Restricted) ')
            ->setDescription('Additional allowable choices for purpose when creating a resource, for those with "Manage All Resources" rights.');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, []);

        $this->addSection('Resource Settings');

        $this->setSectionsHeader('manage_resource_settings');

        $this->setSettingManager(null);

        return $this;
    }
}
