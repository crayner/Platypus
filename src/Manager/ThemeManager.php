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

use App\Util\ThemeVersionHelper;
use App\Util\UserHelper;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function getInstalledThemes(): array
    {
        $fs = new Finder();
        $path = $this->settingManager->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'platypus-template';
        $fs->directories()->in($path);
        $fs->depth(0);
        $templates = [];
        foreach($fs as $dir) {
            $template['name'] = $dir->getRelativePathname();
            $version = '\PlatypusTemplate\\'.ucfirst($template['name']).'\Util\VersionHelper';
            $helper = $path.DIRECTORY_SEPARATOR.$template['name'].DIRECTORY_SEPARATOR.'Util'.DIRECTORY_SEPARATOR.'VersionHelper.php';

            if (file_exists($helper)) {
                include $helper;

                if (class_exists($version))
                    $template['version'] = new $version();
                else
                    $template['version'] = new ThemeVersionHelper($template['name']);

                if (! $this->isValidHelper($template['version']))
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
    private $settingManager;

    /**
     * ThemeManager constructor.
     * @param SettingManager $settingManager
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    /**
     * getCurrentThemeName
     *
     * @return string
     */
    public function getCurrentThemeName($default = false): string
    {
        $theme = $this->settingManager->get('current.theme', 'PlatypusTemplateOriginal');
        if ($default)
            return $theme;
        $user = UserHelper::getCurrentUser();
        if ($user instanceof UserInterface)
        {
            if (! empty($user->getUserSetting('current.theme')))
                $theme = $user->getUserSetting('current.theme');
        }
        return $theme;
    }

    /**
     * isValidHelper
     *
     * @param object $helper
     * @return bool
     */
    private function isValidHelper(object $helper): bool
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
}