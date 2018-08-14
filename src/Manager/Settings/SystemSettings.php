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
use App\Validator\OrgName;
use App\Validator\Yaml;
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
class SystemSettings implements SettingCreationInterface
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
        $settings = [];

        $setting = $sm->createOneByName('system.absolute.url');

        $setting->setName('system.absolute.url')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('system')
            ->__set('displayName', 'Base URL')
            ->__set('description', 'The address at which the whole system resides.');
        if (empty($setting->getValue())) {
            $setting->setValue($this->getRequest()->server->get('REQUEST_SCHEME').'://'.$this->getRequest()->server->get('SERVER_NAME'))
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.absolute.path');

        $setting->setName('system.absolute.path')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('system')
            ->__set('displayName', 'Base Path')
            ->__set('description', 'The local file server path to the system.');
        if (empty($setting->getValue())) {
            $setting->setValue($this->getRequest()->server->get('DOCUMENT_ROOT'))
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.name');

        $setting->setName('system.name')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('string')
            ->__set('displayName', 'System Name')
            ->__set('description', '');
        if (empty($setting->getValue())) {
            $setting->setValue('Busybee')
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.index.text');

        $setting->setName('system.index.text')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('html')
            ->__set('displayName', 'Index Page Text')
            ->__set('description', 'Text displayed in system\'s welcome page.  If left blank, then the system default is used.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.install.type');

        $setting->setName('system.install.type')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'Install Type')
            ->__set('description', 'The purpose of this installation of Busybee');
        if (empty($setting->getValue())) {
            $setting->setValue('production')
                ->__set('choice', ['production','testing','development'])
                ->setValidators(null)
                ->setDefaultValue('production')
                ->__set('translateChoice', 'System')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.stats.collection');

        $setting->setName('system.stats.collection')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('boolean')
            ->__set('displayName', 'Statistics Collection')
            ->__set('description', 'To track Busybee uptake, the system tracks basic data (current URL, install type, organisation name) on each install. Do you want to help?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'System Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
// Start New Section
        $settings = [];

        $setting = $sm->createOneByName('org.name');

        $setting->setName('org.name')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('array')
            ->__set('displayName', 'Organisation Name')
            ->__set('description', 'Consists of a long and short name.');
        if (empty($setting->getValue())) {
            $setting->setValue(['long' => 'Busybee Learning', 'short' => 'BEE'])
                ->__set('choice', null)
                ->setValidators([
                    new Yaml(),
                    new OrgName(),
                ])
                ->setDefaultValue(['long' => 'Busybee Learning', 'short' => 'BEE'])
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.email');

        $setting->setName('org.email')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('string')
            ->__set('displayName', 'Organisation Email')
            ->__set('description', 'General email address for the school.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators([
                    new Email(),
                ])
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.logo');

        $setting->setName('org.logo')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('image')
            ->__set('displayName', 'Organisation Logo')
            ->__set('description', 'Relative path to site logo. If empty, the template logo is used.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators([
                    new Logo(),
                ])
                ->setDefaultValue('bundles/platypustemplateoriginal/img/bee.png')
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.administrator');

        $setting->setName('org.administrator')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'System Administrator')
            ->__set('description', 'The staff member who receives notifications for system events.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', StaffManager::getStaffList())
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', false)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.dba');

        $setting->setName('org.dba')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'Database Administrator')
            ->__set('description', 'The staff member who receives notifications for data events.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', StaffManager::getStaffList())
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', false)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.admissions');

        $setting->setName('org.admissions')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'Admissions Administrator')
            ->__set('description', 'The staff member who receives notifications for admissions events.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', StaffManager::getStaffList())
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', false)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('org.hr');

        $setting->setName('org.hr')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'Human Resources Administrator')
            ->__set('description', 'The staff member who receives notifications for staffing events.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', StaffManager::getStaffList())
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', false)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Organisation Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

// Start New Section
        $settings = [];

        $setting = $sm->createOneByName('security.password.settings:min_length');

        $setting->setName('security.password.settings:min_length')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'Password - Minimum Length')
            ->__set('description', 'Minimum acceptable password length required by all users.')
            ->setParameter(true, $sm,8)
            ->__set('choice', [4,5,6,7,8,9,10,11,12])
            ->setValidators([
                new Range(['min' => 4])
            ])
            ->setDefaultValue(8)
            ->__set('translateChoice', null)
            ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('security.password.settings:mixed_case');

        $setting->setName('security.password.settings:mixed_case')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('boolean')
            ->__set('displayName', 'Password - Alpha Requirement')
            ->__set('description', 'Require both upper and lower case alpha characters for all user passwords?')
            ->setParameter(true, $sm, true)
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('security.password.settings:numbers');

        $setting->setName('security.password.settings:numbers')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('boolean')
            ->__set('displayName', 'Password - Numeric Requirement')
            ->__set('description', 'Require at least one numeric character for all user passwords?')
            ->setParameter(true, $sm, true)
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('security.password.settings:specials');

        $setting->setName('security.password.settings:specials')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('boolean')
            ->__set('displayName', 'Password - Non-Alphanumeric Requirement')
            ->__set('description', 'Require at least one non-alphanumeric character (e.g. punctuation mark or space) in all user passwords?')
            ->setParameter(true, $sm,true)
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ;
        $settings[] = $setting;

        $section['name'] = 'Security Settings - Password Policy';
        $section['description'] = 'Changes in password policy will be applied the next time you attempt to sign in.';
        $section['settings'] = $settings;

        $sections[] = $section;
// Start New Section
        $settings = [];

        $setting = $sm->createOneByName('idle_timeout');

        $setting->setName('idle_timeout')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('number')
            ->__set('displayName', 'Session Duration')
            ->__set('description', 'Time, in minutes, before system logs a user out. Should be less than PHP\'s session.gc_maxlifetime option.')
            ->setParameter(true, $sm,15)
            ->__set('choice', null)
            ->setValidators([
                new Range(['max' => 30, 'min' => 5])
            ])
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
        ;
        $settings[] = $setting;

        $section['name'] = 'Security Settings - Miscellaneous';
        $section['description'] = 'Changes in password policy will be applied the next time you attempt to sign in.';
        $section['settings'] = $settings;

        $sections[] = $section;
// Start New Section
        $settings = [];

        $setting = $sm->createOneByName('country');

        $setting->setName('country')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'Country')
            ->__set('description', 'The country the school is located in.')
            ->setParameter(true, $sm,15)
            ->__set('choice', array_flip(Intl::getRegionBundle()->getCountryNames()))
            ->setValidators([
                new Country(),
            ])
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
        ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.first_day_of_the_week');

        $setting->setName('system.first_day_of_the_week')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'First Day Of The Week')
            ->__set('description', 'On which day should the week begin?');
        if (empty($setting->getValue())) {
            $setting->setValue('mon')
                ->__set('choice', ['mon','sun'])
                ->setValidators(null)
                ->setDefaultValue('mon')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('timezone');

        $setting->setName('timezone')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'Timezone')
            ->__set('description', 'The timezone where the school is located.')
            ->setParameter(true, $sm,'UTC')
            ->__set('choice', InternationalHelper::getTimezones())
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
        ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('currency');

        $setting->setName('currency')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'Currency')
            ->__set('description', 'System-wde currency for financial transactions. Support for online payment in this currency depends on your credit card gateway: please consult their support documentation.')
            ->setParameter(true, $sm,'AUD')
            ->__set('choice', array_flip(Intl::getCurrencyBundle()->getCurrencyNames()))
            ->setValidators([
                new Currency(),
            ])
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
        ;
        $settings[] = $setting;

        $setting = $sm->createOneByName('locale');

        $setting->setName('locale')
            ->__set('role', 'ROLE_SYSTEM_ADMIN')
            ->setType('choice')
            ->__set('displayName', 'Language Setting')
            ->__set('description', 'This setting defaults the system to the selected language. Language can be over ridden by individual users.')
            ->setParameter(true, $sm,'en')
            ->__set('choice', array_flip(TranslationManager::$languages))
            ->setValidators([
                new Language(),
            ])
            ->setDefaultValue('en')
            ->__set('translateChoice', false)
        ;
        $settings[] = $setting;

        $section['name'] = 'Localisation';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

// Start New Section
        $settings = [];

        $setting = $sm->createOneByName('system.web_link');

        $setting->setName('system.web_link')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('string')
            ->__set('displayName', 'Link To Web')
            ->__set('description', 'The link that points to the school\'s website');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators([
                    new Url(),
                ])
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.pagination');

        $setting->setName('system.pagination')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('string')
            ->__set('displayName', 'Pagination Count')
            ->__set('description', 'Must be numeric. Number of records shown per page.');
        if (empty($setting->getValue())) {
            $setting->setValue(25)
                ->__set('choice', null)
                ->setValidators([
                    new NotBlank(),
                    new Integer(),
                    new Range(['min' => 10, 'max' => 100]),
                ])
                ->setDefaultValue(25)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.analytics');

        $setting->setName('system.analytics')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('text')
            ->__set('displayName', 'Analytics')
            ->__set('description', 'Javascript code to integrate statistics, such as Google Analytics.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('system.default_assessment_scale');

        $setting->setName('system.default_assessment_scale')
            ->__set('role', 'ROLE_REGISTRAR')
            ->setType('choice')
            ->__set('displayName', 'Default Assessment Scale')
            ->__set('description', 'This is the scale used as a default where assessment scales need to be selected.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', ScaleManager::getScaleList(true))
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', false)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_system_settings';

        $this->sections = $sections;
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
