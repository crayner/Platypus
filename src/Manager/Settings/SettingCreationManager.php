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
 * Date: 29/09/2018
 * Time: 07:28
 */
namespace App\Manager\Settings;

use App\Entity\Setting;
use App\Manager\SettingManager;
use App\Organism\SettingCache;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SettingCreationManager
 * @package App\Manager\Settings
 */
abstract class SettingCreationManager implements SettingCreationInterface
{
    /**
     * @var array|null
     */
    private $settingsCache;

    /**
     * getSettingsCache
     *
     * @return array
     */
    public function getSettingsCache(): array
    {
        return $this->settingsCache = $this->settingsCache ?: [];
    }

    /**
     * setSettingsCache
     *
     * @param array|null $settingsCache
     * @return SettingCreationManager
     */
    public function setSettingsCache(?array $settingsCache): SettingCreationManager
    {
        $this->settingsCache = $settingsCache ?: [];
        return $this;
    }

    /**
     * addSetting
     *
     * @param Setting $setting
     * @param array $arguments
     * @return SettingCreationManager
     */
    public function addSetting(Setting $setting, array $arguments = []): SettingCreationManager
    {
        $w = new SettingCache($setting);
        $w->handleArguments($arguments, $this->getSettingManager());
        $this->getSettingsCache();
        $w->getValue();
        $this->settingsCache[] = $w;
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
        return $this->sections = $this->sections ?: [];
    }

    /**
     * addSection
     *
     * @param string $name
     * @param string $description
     * @return $this
     */
    public function addSection(string $name, string $description = '', array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'description_parameters' => [],
                'colour' => 'warning',
            ]
        );
        $options = $resolver->resolve($options);

        $this->getSections();
        $section = [];
        $section['name'] = $name;
        $section['description'] = $description;
        $section['settings'] = $this->getSettingsCache();
        $section['description_parameters'] = $options['description_parameters'];
        $section['colour'] = $options['colour'];

        $this->setSettingsCache(null);
        $this->sections[] = $section;
        return $this;
    }

    /**
     * @var SettingManager|null
     */
    private $settingManager;

    /**
     * @return SettingManager|null
     */
    public function getSettingManager(): ?SettingManager
    {
        return $this->settingManager;
    }

    /**
     * @param SettingManager $settingManager
     * @return SettingCreationManager
     */
    public function setSettingManager(?SettingManager $settingManager): SettingCreationManager
    {
        $this->settingManager = $settingManager;
        return $this;
    }

    /**
     * @var string|null
     */
    private $sectionsHeader;

    /**
     * getSectionsHeader
     *
     * @return null|string
     */
    public function getSectionsHeader(): ?string
    {
        return $this->sectionsHeader;
    }

    /**
     * setSectionsHeader
     *
     * @param null|string $sectionsHeader
     * @return SettingCreationManager
     */
    public function setSectionsHeader(?string $sectionsHeader): SettingCreationManager
    {
        $this->sectionsHeader = $sectionsHeader;
        return $this;
    }
}