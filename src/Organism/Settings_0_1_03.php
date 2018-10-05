<?php
namespace App\Organism;

use App\Manager\SettingInterface;

class Settings_0_1_03 implements SettingInterface
{
	const VERSION = '0.1.03';

	/**
	 * @return string
	 */
	public function getSettings(): string
	{
		return file_get_contents(__DIR__ . '/Settings/settings_0.1.03.yaml');
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return get_class();
	}
}