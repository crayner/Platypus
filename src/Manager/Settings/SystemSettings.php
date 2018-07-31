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

use App\Manager\SettingManager;
use App\Manager\StaffManager;
use App\Validator\Logo;
use App\Validator\OrgName;
use App\Validator\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;

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
            ->setType('string')
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
                ->__set('choice', ['method' => StaffManager::class.'::staffList'])
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
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
                ->__set('choice', ['method' => StaffManager::class.'::staffList'])
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
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
                ->__set('choice', ['method' => StaffManager::class.'::staffList'])
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
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
                ->__set('choice', ['method' => StaffManager::class.'::staffList'])
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', null)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Organisation Settings';
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
