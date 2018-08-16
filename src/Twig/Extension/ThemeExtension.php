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
 * Date: 16/08/2018
 * Time: 14:18
 */
namespace App\Twig\Extension;

use App\Manager\ThemeManager;
use App\Util\PersonHelper;
use Twig\Extension\AbstractExtension;

/**
 * Class ThemeExtension
 * @package App\Twig\Extension
 */
class ThemeExtension extends AbstractExtension
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * ThemeExtension constructor.
     * @param ThemeManager $themeManager
     */
    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }
    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getBackgroundImage', [$this, 'getBackgroundImage']),
            new \Twig_SimpleFunction('getThemeName', [$this, 'getCurrentThemeName']),
        ];
    }

    /**
     * getCurrentThemeName
     *
     * @return string
     */
    public function getCurrentThemeName(): string
    {
        return $this->themeManager::getCurrentThemeName();
    }

    /**
     * getBackgroundImage
     *
     * @return string
     */
    public function getBackgroundImage(): string
    {
        $image = 'build/static/images/backgroundPage.jpg';
        $theme = $this->themeManager->getCurrentTheme();

        $image = $theme->getBackgroundImage() ?: $image;

        if (PersonHelper::hasPerson())
            $image = PersonHelper::getPerson()->getPersonalBackground() ?: $image;

        return $image;
    }
}