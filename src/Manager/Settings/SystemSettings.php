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
 * Date: 22/06/2018
 * Time: 16:15
 */
namespace App\Manager\Settings;

use App\Manager\ScaleManager;
use App\Manager\SettingManager;
use App\Manager\StaffManager;
use App\Manager\TranslationManager;
use App\Util\InternationalHelper;
use App\Validator\Logo;
use Hillrange\Form\Validator\Integer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Language;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class DashboardSettings
 * @package App\Manager\Settings
 */
class SystemSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'System';
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
        $setting = $sm->createOneByName('system.absolute_url')
            ->setSettingType('system')
            ->setDisplayName('Base URL')
            ->setValidators([
                new Url(),
            ])
            ->setDescription('The address at which the whole system resides.')
            ->setValue($this->getRequest()->server->get('REQUEST_SCHEME').'://'.$this->getRequest()->server->get('SERVER_NAME'));
        $this->addSetting($setting,[]);

        $setting = $sm->createOneByName('system.absolute_path')
            ->setSettingType('system')
            ->setDisplayName('Base Path')
            ->setValidators(null)
            ->setDescription('The local file server path to the system.')
            ->setValue($this->getRequest()->server->get('DOCUMENT_ROOT'));
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.system_name')
            ->setSettingType('string')
            ->setDisplayName('System Name')
            ->setValidators(null)
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('Busybee');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.index_text')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDisplayName('Index Page Text')
            ->setDescription('Text displayed in system\'s welcome page.  If left blank, then the system default is used.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.install_type')
            ->setSettingType('enum')
            ->setDisplayName('Install Type')
            ->setDescription('How are you using this installation of Busybee')
            ->setValidators(null)
            ->setFlatChoices(['production','testing','development']);
        if (in_array($setting->getValue(), $setting->getChoices()))
            $setting->setValue($setting->getValue());
        else
            $setting->setValue('production');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.stats_collection')
            ->setSettingType('boolean')
            ->setDisplayName('Statistics Collection')
            ->setValidators(null)
            ->setDescription('To track Busybee uptake, the system tracks basic data (current URL, install type, organisation name) on each install. Do you want to help?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('System Settings');

// Start New Section
        $setting = $sm->createOneByName('org.name.long')
            ->setSettingType('string')
            ->setDisplayName('Organisation Name')
            ->setValidators(null)
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('Busybee');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('org.name.short')
            ->setSettingType('string')
            ->setDisplayName('Short Organisation Name')
            ->setValidators(null)
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue('BEE');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('org.email')
            ->setValidators([
                new Email(),
            ])
            ->setSettingType('string')
            ->setDisplayName('Organisation Email')
            ->setDescription('General email address for the school.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('org.logo')
            ->setSettingType('image')
            ->setDisplayName('Organisation Logo')
            ->setValidators([
                new Logo(),
            ])
            ->setDescription('Relative path to site logo. If empty, the template logo is used.');
        if (empty($setting->getValue()))
            $setting->setValue('build/static/images/bee.png');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.organisation_administrator')
            ->setSettingType('enum')
            ->setDisplayName('System Administrator')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setValidators(null)
            ->setDescription('The staff member who receives notifications for system events.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.organisation_dba')
            ->setSettingType('enum')
            ->setDisplayName('Database Administrator')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setValidators(null)
            ->setDescription('The staff member who receives notifications for data events.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.organisation_admissions')
            ->setSettingType('enum')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setValidators(null)
            ->setDisplayName('Admissions Administrator')
            ->setDescription('The staff member who receives notifications for admissions events.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('system.organisation_hr')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setValidators(null)
            ->setSettingType('enum')
            ->setDisplayName('Human Resources Administrator')
           ->setDescription('The staff member who receives notifications for staffing events.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Organisation Settings');

        // Start New Section
        $setting = $sm->createOneByName('security.password.settings:min_length')
            ->setSettingType('enum')
            ->setDisplayName('Password - Minimum Length')
            ->setDescription('Minimum acceptable password length required by all users.')
            ->setFlatChoices([4,5,6,7,8,9,10,11,12,13,14,15,16], false)
            ->setValidators([
                new Range(['min' => 4, 'max' => 16])
            ]);
        $this->addSetting($setting, ['parameter' => true, 'default' => 8]);

        $setting = $sm->createOneByName('security.password.settings:mixed_case')
            ->setSettingType('boolean')
            ->setDisplayName('Password - Alpha Requirement')
            ->setDescription('Require both upper and lower case alpha characters for all user passwords?')
            ->setValidators(null);
        $this->addSetting($setting, ['parameter' => true, 'default' => true]);

        $setting = $sm->createOneByName('security.password.settings:numbers')
            ->setSettingType('boolean')
            ->setDisplayName('Password - Numeric Requirement')
            ->setDescription('Require at least one numeric character for all user passwords?')
            ->setValidators(null);
        $this->addSetting($setting, ['parameter' => true, 'default' => true]);

        $setting = $sm->createOneByName('security.password.settings:specials')
            ->setSettingType('boolean')
            ->setDisplayName('Password - Non-Alphanumeric Requirement')
            ->setDescription('Require at least one non-alphanumeric character (e.g. punctuation mark or space) in all user passwords?')
            ->setValidators(null);
        $this->addSetting($setting, ['parameter' => true, 'default' => true]);

        $this->addSection('Security Settings - Password Policy', 'Changes in password policy will be applied the next time you attempt to sign in.');

        // Start New Section
        $setting = $sm->createOneByName('idle_timeout')
            ->setDisplayName('Session Duration')
            ->setDescription('Time, in minutes, before system logs a user out. Should be less than PHP\'s session.gc_maxlifetime option.')
            ->setSettingType('number')
            ->setValidators([
                new Range(['max' => 30, 'min' => 5])
            ]);
        $this->addSetting($setting, ['parameter' => true, 'default' => 15]);

        $this->addSection('Security Settings - Miscellaneous', 'Changes in password policy will be applied the next time you attempt to sign in.');

        // Start New Section
        $setting = $sm->createOneByName('country')
            ->setSettingType('enum')
            ->setDisplayName('Country')
            ->setDescription('The country the school is located in.')
            ->setChoices(array_flip(Intl::getRegionBundle()->getCountryNames()))
            ->setValidators([
                new Country(),
            ]);
        $this->addSetting($setting, ['parameter' => true, 'default' => 'AU']);

        $setting = $sm->createOneByName('system.first_day_of_the_week')
            ->setSettingType('enum')
            ->setDisplayName('First Day Of The Week')
            ->setFlatChoices(['mon','sun'])
            ->setValidators(null)
            ->setDescription('On which day should the week begin?');
        if (empty($setting->getValue()))
            $setting->setValue('mon');
        $this->addSetting($setting);


        $z = InternationalHelper::getTimezones();
        $zones = [];
        foreach(InternationalHelper::getTimezones() as $region=>$data)
        {
            $zones[$region] = $data;
        }

        $setting = $sm->createOneByName('timezone')
            ->setSettingType('enum')
            ->setDisplayName('Timezone')
            ->setDescription('The timezone where the school is located.')
            ->setChoices($zones)
            ->setValidators(null);
        $this->addSetting($setting, ['parameter' => true, 'default' => 'UTC']);

        $setting = $sm->createOneByName('currency')
            ->setSettingType('enum')
            ->setDisplayName('Currency')
            ->setDescription('System-wde currency for financial transactions. Support for online payment in this currency depends on your credit card gateway: please consult their support documentation.')
            ->setChoices(array_flip(Intl::getCurrencyBundle()->getCurrencyNames()))
            ->setValidators([
                new Currency(),
            ]);
        $this->addSetting($setting, ['parameter' => true, 'default' => 'AUD']);

        $setting = $sm->createOneByName('locale')
            ->setSettingType('enum')
            ->setDisplayName('Language Setting')
            ->setDescription('This setting defaults the system to the selected language. Language can be over ridden by individual users.')
            ->setChoices(array_flip(TranslationManager::$languages))
            ->setValidators([
                new Language(),
            ]);
        $this->addSetting($setting, ['parameter' => true, 'default' => 'en']);

        $setting = $sm->createOneByName('date.format.long')
            ->setSettingType('string')
            ->setDisplayName('Long Date Format')
            ->setDescription('The format used to display a long date using details as described at "https://www.w3schools.com/php/func_date_date_format.asp".');
        if (empty($setting->getValue()))
            $setting->setValue('D, jS M/Y');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('date.format.short')
            ->setSettingType('string')
            ->setDisplayName('Short Date Format')
            ->setDescription('The format used to display a short date using details as described at "https://www.w3schools.com/php/func_date_date_format.asp".');
        if (empty($setting->getValue()))
            $setting->setValue('d M/Y');
        $this->addSetting($setting, []);

        $this->addSection('Localisation');

        // Start New Section
        $setting = $sm->createOneByName('system.web_link')
            ->setSettingType('string')
            ->setValidators([
                new Url(),
            ])
            ->setDisplayName('Link To Web')
            ->setDescription('The link that points to the school\'s website');
        $this->addSetting($setting);

        $setting = $sm->createOneByName('system.pagination')
            ->setSettingType('integer')
            ->setDisplayName('Pagination Count')
            ->setValidators([
                new NotBlank(),
                new Integer(),
                new Range(['min' => 10, 'max' => 100]),
            ])
            ->setDescription('Must be numeric. Number of records shown per page.');
        if (empty($setting->getValue()))
            $setting->setValue(25);
        $this->addSetting($setting);

        $setting = $sm->createOneByName('system.analytics')
            ->setSettingType('text')
            ->setDisplayName('Analytics')
            ->setDescription('Javascript code to integrate statistics, such as Google Analytics.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting);

        $setting = $sm->createOneByName('system.default_assessment_scale')
            ->setSettingType('enum')
            ->setDisplayName('Default Assessment Scale')
            ->setChoices(ScaleManager::getScaleList(true))
            ->setDescription('This is the scale used as a default where assessment scales need to be selected.');
        $this->addSetting($setting);

        $this->addSection('Miscellaneous');

        $this->setSectionsHeader('manage_system_settings');

        $this->setSettingManager(null);
        return $this;
    }

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     * @return SystemSettings
     */
    public function setRequest(Request $request): SystemSettings
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * SystemSettings constructor.
     */
    public function __construct(array $options = [])
    {
        $this->setRequest($options['request']);
    }
}
