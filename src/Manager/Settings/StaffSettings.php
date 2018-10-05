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
 * Date: 20/09/2018
 * Time: 09:06
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Validator\Twig;

/**
 * Class StaffSettings
 * @package App\Manager\Settings
 */
class StaffSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Staff';
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

        $setting = $sm->createOneByName('staff.salary_scale_positions')
            ->setSettingType('array')
            ->setDisplayName('Staff Salary Scale Positions')
            ->setDescription('List of salary scale positions, from lowest to highest.');
        if (empty($setting->getValue()))
            $setting->setValue(['1','2','3','4','5','6','7','8','9','10']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.responsibility_posts')
            ->setSettingType('array')
            ->setDisplayName('Staff Responsibility Posts')
            ->setDescription('List of posts carrying extra responsibilities.');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.job_opening_description_template')
            ->setSettingType('html')
            ->setDisplayName('Staff Job Opening Description Template')
            ->setDescription('Default HTML contents for the Job Opening Description field.');
        if (empty($setting->getValue()))
            $setting->setValue('<table style=\'width: 100%\'>
	<tr>
		<td colspan=2 style=\'vertical-align: top\'>
			<span style=\'text-decoration: underline; font-weight: bold\'>Job Description</span><br/>
			<br/>
		</td>
	</tr>
	<tr>
		<td style=\'width: 50%; vertical-align: top\'>
			<span style=\'text-decoration: underline; font-weight: bold\'>Responsibilities</span><br/>
			<ul style=\'margin-top:0px\'>
				<li></li>
				<li></li>
			</ul>
		</td>
		<td style=\'width: 50%; vertical-align: top\'>
			<span style=\'text-decoration: underline; font-weight: bold\'>Required Skills/Characteristics</span><br/>
			<ul style=\'margin-top:0px\'>
				<li></li>
				<li></li>
			</ul>
		</td>
	</tr>
	<tr>
		<td style=\'width: 50%; vertical-align: top\'>
			<span style=\'text-decoration: underline; font-weight: bold\'>Remuneration</span><br/>
			<ul style=\'margin-top:0px\'>
				<li></li>
				<li></li>
			</ul>
		</td>
		<td style=\'width: 50%; vertical-align: top\'>
			<span style=\'text-decoration: underline; font-weight: bold\'>Other Details </span><br/>
			<ul style=\'margin-top:0px\'>
				<li></li>
				<li></li>
			</ul>
		</td>
	</tr>
</table>');
        $this->addSetting($setting, []);

        $this->setSectionsHeader('staff_settings');


        $this->addSection('Field Values');

        $setting = $sm->createOneByName('system.name_format_staff_formal')
            ->setSettingType('twig')
            ->setDisplayName('Staff Formal Name Format')
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('{{ title }} {{ preferredName|slice(1,1) }}. {{ surname }}');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.name_format_staff_formal_reverse')
            ->setSettingType('twig')
            ->setDisplayName('Staff Formal Name Format - Reverse')
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('{{ title }} {{ surname }} {{ preferredName|slice(1,1) }}.');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.name_format_staff_in_formal')
            ->setSettingType('twig')
            ->setDisplayName('Staff Informal Name Format')
             ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('{{ preferredName }} {{ surname }}');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.name_format_staff_in_formal_reverse')
            ->setSettingType('twig')
            ->setDisplayName('Staff Informal Name Format - Reverse')
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('{{ surname}}, {{ preferredName }}');
        $this->addSetting($setting, []);

        $this->addSection('Name Formats', 'How should staff names be formatted? Choose from [title], [preferredName], [surname]. Use a colon to limit the number of letters, for example [preferredName:1] will use the first initial.');

        $this->setSettingManager(null);

        return $this;
    }
}
