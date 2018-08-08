<?php
namespace App\Twig\Extension;

use App\Manager\MenuManager;
use App\Manager\SettingManager;
use App\Manager\ThemeManager;
use Twig\Extension\AbstractExtension;

class SettingExtension extends AbstractExtension
{
	/**
	 * @var SettingManager
	 */
	private $settingManager;

	/**
	 * @var MenuManager
	 */
	private $menuManager;

    /**
     * @var ThemeManager
     */
	private $themeManager;

	/**
	 * SettingExtension constructor.
	 *
	 * @param SettingManager $sm
	 * @param MenuManager    $menuManager
	 */
	public function __construct(SettingManager $sm, MenuManager $menuManager, ThemeManager $themeManager)
	{
		$this->settingManager   = $sm;
		$this->menuManager      = $menuManager;
        $this->themeManager     = $themeManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('getSetting', array($this->settingManager, 'get')),
			new \Twig_SimpleFunction('getParameter', array($this->settingManager, 'getParameter')),
			new \Twig_SimpleFunction('getMenu', array($this->menuManager, 'getMenu')),
			new \Twig_SimpleFunction('getMenuItems', array($this, 'getMenuItems')),
			new \Twig_SimpleFunction('testMenuItem', array($this->menuManager, 'testMenuItem')),
			new \Twig_SimpleFunction('menuRequired', array($this->menuManager, 'menuRequired')),
			new \Twig_SimpleFunction('array_flip', array($this, 'arrayFlip')),
            new \Twig_SimpleFunction('getSection', array($this->menuManager, 'getSection')),
            new \Twig_SimpleFunction('displayBoolean', array($this, 'displayBoolean')),
            new \Twig_SimpleFunction('settingLink', array($this->settingManager, 'settingLink'), ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('exit', [$this, 'exit']),
            new \Twig_SimpleFunction('dumpSetting', [$this, 'dumpSetting']),
            new \Twig_SimpleFunction('getThemeName', [$this->themeManager, 'getCurrentThemeName']),
		);
	}

	/**
	 * @param $node
	 *
	 * @return mixed
	 */
	public function getMenuItems($node)
	{
		return $this->menuManager->getMenuItems($node);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'system_twig_extension';
	}

    /**
     * @param array $data
     * @return array
     */
    public function arrayFlip(array $data): array
	{
		return array_flip($data);
	}

    /**
     * @param bool $value
     * @param string $true
     * @param string $false
     * @return string
     */
    public function displayBoolean(bool $value, string $true = 'Yes', string $false = 'No'): string
    {
        return $value ? $true : $false ;
    }

    /**
     * exit
     *
     */
    public function exit()
    {
        die();
    }

    public function dumpSetting()
    {
        dump($this->settingManager);
    }
}