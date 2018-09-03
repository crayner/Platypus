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
 * Date: 3/08/2018
 * Time: 16:34
 */
namespace App\Manager;

use App\Util\PersonHelper;
use App\Util\ThemeVersionHelper;
use Symfony\Component\Finder\Finder;

/**
 * Class ThemeManager
 * @package App\Manager
 */
class ThemeManager
{
    /**
     * getInstalledThemes
     *
     * @return array
     */
    public static function getInstalledThemes(): array
    {
        $fs = new Finder();
        $path = self::$settingManager->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'platypus-template';
        $fs->directories()->in($path);
        $fs->depth(0);
        $templates = [];
        foreach($fs as $dir) {
            $template['name'] = $dir->getRelativePathname();
            $version = '\PlatypusTemplate\\'.ucfirst($template['name']).'\Util\VersionHelper';
            $helper = $path.DIRECTORY_SEPARATOR.$template['name'].DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR . 'VersionHelper.php';

            if (file_exists($helper)) {
                if (! class_exists($version))
                    include $helper;

                if (class_exists($version))
                    $template['version'] = new $version();
                else
                    $template['version'] = new ThemeVersionHelper($template['name']);

                if (! self::isValidHelper($template['version']))
                    $template['version'] = new ThemeVersionHelper($template['name']);
            } else
                $template['version'] = new ThemeVersionHelper($template['name']);
            $templates[] = $template;
        }
        return $templates;
    }

    /**
     * @var SettingManager
     */
    private static $settingManager;

    /**
     * ThemeManager constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager, PersonHelper $personHelper)
    {
        self::$settingManager = $settingManager;
    }

    /**
     * getCurrentThemeName
     *
     * @return null|string
     */
    public static function getCurrentThemeName($default = false): ?string
    {
        return self::getCurrentTheme($default)->getTemplateName();
    }

    /**
     * getCurrentThemeName
     *
     * @return null|\App\Manager\Interfaces\ThemeVersionHelper
     */
    public static function getCurrentTheme(bool $default = false): ?\App\Manager\Interfaces\ThemeVersionHelper
    {
        $themeName = self::$settingManager->get('current.theme', 'PlatypusTemplateOriginal');

        foreach(self::getInstalledThemes() as $theme)
            if ($theme['version']->getTemplateName() === $themeName)
                break;
        if ($default)
            return $theme['version'];

        if (PersonHelper::hasPerson())
            $themeName = PersonHelper::getPerson()->getPersonalTheme() ?: $themeName;
        foreach(self::getInstalledThemes() as $theme)
            if ($theme['version']->getTemplateName() === $themeName)
                break;

        return $theme['version'];
    }

    /**
     * isValidHelper
     *
     * @param \object $helper
     * @return bool
     */
    private static function isValidHelper($helper): bool
    {
        if (! $helper instanceof \App\Manager\Interfaces\ThemeVersionHelper)
            return false;

        $funcs = [
            'getName',
            'getVersion',
            'getDescription',
            'getAuthor',
            'getURL',
            'getTemplateName'
        ];

        foreach($funcs as $func) {
            if (! method_exists($helper, $func))
                return false;
        }

        return true;
    }

    /**
     * getThemeChoiceList
     *
     * @return array
     */
    public static function getThemeChoiceList(): array
    {
        $results = [];
        foreach(self::getInstalledThemes() as $theme)
            $results[$theme['version']->getName()] = $theme['version']->getTemplateName();

        return $results;
    }
}