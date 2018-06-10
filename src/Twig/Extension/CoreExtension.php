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
 * Date: 8/06/2018
 * Time: 16:46
 */
namespace App\Twig\Extension;

use App\Manager\ScriptManager;
use App\Manager\VersionManager;
use Twig\Extension\AbstractExtension;

/**
 * Class CoreExtension
 * @package App\Twig\Extension
 */
class CoreExtension extends AbstractExtension
{
    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * CoreExtension constructor.
     * @param ScriptManager $scriptManager
     */
    public function __construct(ScriptManager $scriptManager)
    {
        $this->scriptManager = $scriptManager;
    }

    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getVersion', [$this, 'getVersion']),
            new \Twig_SimpleFunction('get_setting', [$this, 'getSetting']),
            new \Twig_SimpleFunction('get_section', [$this, 'getSection']),
            new \Twig_SimpleFunction('get_parameter', [$this, 'crashBurn']),
            new \Twig_SimpleFunction('get_UserSetting', [$this, 'crashBurn']),
            new \Twig_SimpleFunction('get_CurrentCalendarName', [$this, 'crashBurn']),
            new \Twig_SimpleFunction('getMessageManager', [$this, 'getSection']),
            new \Twig_SimpleFunction('hideSection', [$this, 'hideSection']),
            new \Twig_SimpleFunction('addScript', array($this->scriptManager, 'addScript')),
            new \Twig_SimpleFunction('getScripts', array($this->scriptManager, 'getScripts')),
            new \Twig_SimpleFunction('isInstanceof', array($this, 'isInstanceof')),
        ];
    }

    /**
     * getVersion
     *
     * @return string
     */
    public function getVersion(): string
    {
        return VersionManager::VERSION;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'core_extension';
    }

    public function crashBurn()
    {
        trigger_error('Fix this NOW!!!');
    }

    public function getSetting($name, $default = null)
    {
        return $default;
    }

    public function getSection()
    {
        return [];
    }

    public function hideSection()
    {
        return false;
    }

    /**
     * isInstanceof
     *
     * @param $var
     * @param string $instance
     * @return bool
     */
    public function isInstanceof($var, string $instance)
    {
        return ($var instanceof $instance);
    }
}