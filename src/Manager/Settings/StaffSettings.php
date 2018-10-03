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

/**
 * Class StaffSettings
 * @package App\Manager\Settings
 */
class StaffSettings implements SettingCreationInterface
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
        $sections = [];
        $sections['header'] = 'staff_settings';
        $settings = [];

        $setting = $sm->createOneByName('staff.salary_scale_positions');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->setDisplayName('Staff Salary Scale Positions')

            ->setValidators(null)
            ->setDefaultValue(['1','2','3','4','5','6','7','8','9','10'])
             ->setDescription('List of salary scale positions, from lowest to highest.');
        if (empty($setting->getValue())) {
            $setting->setValue(['1','2','3','4','5','6','7','8','9','10']);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.responsibility_posts');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->setDisplayName('Staff Responsibility Posts')

            ->setValidators(null)
            ->setDefaultValue([])
             ->setDescription('List of posts carrying extra responsibilities.');
        if (empty($setting->getValue())) {
            $setting->setValue([]);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.job_opening_description_template');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->setDisplayName('Staff Job Opening Description Template')

            ->setValidators(null)
            ->setDefaultValue('<table style=\'width: 100%\'>
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
</table>')
             ->setDescription('Default HTML contents for the Job Opening Description field.');
        if (empty($setting->getValue())) {
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
        }
        $settings[] = $setting;

        $section['name'] = 'Field Values';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $this->sections = $sections;
        $settings = [];

        $setting = $sm->createOneByName('system.name_format_staff_formal');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->setDisplayName('Staff Formal Name Format')

            ->setValidators(null)
            ->setDefaultValue('[title] [preferredName:1]. [surname]')
             ->setDescription('');
        if (empty($setting->getValue())) {
            $setting->setValue('[title] [preferredName:1]. [surname]');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.name_format_staff_formal_reverse');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->setDisplayName('Staff Formal Name Format - Reverse')

            ->setValidators(null)
            ->setDefaultValue('[title] [surname], [preferredName:1].')
             ->setDescription('');
        if (empty($setting->getValue())) {
            $setting->setValue('[title] [surname], [preferredName:1].');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.name_format_staff_in_formal');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->setDisplayName('Staff Informal Name Format')

            ->setValidators(null)
            ->setDefaultValue('[preferredName] [surname]')
             ->setDescription('');
        if (empty($setting->getValue())) {
            $setting->setValue('[preferredName] [surname]');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.name_format_staff_in_formal_reverse');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->setDisplayName('Staff Informal Name Format - Reverse')

            ->setValidators(null)
            ->setDefaultValue('[surname], [preferredName]')
             ->setDescription('');
        if (empty($setting->getValue())) {
            $setting->setValue('[surname], [preferredName]');
        }
        $settings[] = $setting;

        $section['name'] = 'Name Formats';
        $section['description'] = 'How should staff names be formatted? Choose from [title], [preferredName], [surname]. Use a colon to limit the number of letters, for example [preferredName:1] will use the first initial.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $this->sections = $sections;
        $settings = [];

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
