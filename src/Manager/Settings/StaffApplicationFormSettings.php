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
 * Date: 19/09/2018
 * Time: 17:06
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;

/**
 * Class StaffApplicationFormSettings
 * @package App\Manager\Settings
 */
class StaffApplicationFormSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'StaffApplicationForm';
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
        $sections['header'] = 'staff_application_form_settings';
        $settings = [];

        $setting = $sm->createOneByName('staff.staff_application_form_introduction');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Introduction')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'Information to display before the form');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.staff_application_form_questions');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Questions')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'HTML text that will appear as questions for the applicant to answer.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.staff_application_form_postscript');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Postscript')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'Information to display at the end of the form.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.staff_application_form_agreement');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Agreement')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.staff_application_form_public_applications');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('displayName', 'Staff Public Applications?')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'If yes, members of the public can submit staff applications.');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.staff_application_form_milestones');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('displayName', 'Staff Application Milestones')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(['Short List','First Interview','Second Interview','Offer Made','Offer Accepted','Contact Issued','Contact Signed'])
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'List of the major steps in the application process. Applicants can be tracked through the various stages.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Short List','First Interview','Second Interview','Offer Made','Offer Accepted','Contact Issued','Contact Signed']);
        }
        $settings[] = $setting;

        $section['name'] = 'General options';
        $section['description'] = '';
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
