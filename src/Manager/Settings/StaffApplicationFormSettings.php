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

use App\Entity\PersonRole;
use App\Manager\SettingManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $sections['header'] = 'application_form_settings';
        $settings = [];

        $setting = $sm->createOneByName('staff.application_form_introduction');

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

        $setting = $sm->createOneByName('staff.application_form_questions');

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

        $setting = $sm->createOneByName('staff.application_form_postscript');

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

        $setting = $sm->createOneByName('staff.application_form_agreement');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Agreement')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue('<p>In submitting this form, I confirm that all information provided above is accurate and complete to the best of my knowledge.</p>')
            ->__set('translateChoice', 'Setting')
            ->__set('description', 'Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything.');
        if (empty($setting->getValue())) {
            $setting->setValue('<p>In submitting this form, I confirm that all information provided above is accurate and complete to the best of my knowledge.</p>');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_public_applications');

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

        $setting = $sm->createOneByName('staff.application_form_milestones');

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

        $types = [];
        $types['Teaching'] = 'html://';
        $types['Support'] = 'html://';
        $results = $sm->getEntityManager()->getRepository(PersonRole::class)->createQueryBuilder('r')
            ->orderBy('r.name')
            ->where('r.category = :staff')
            ->setParameter('staff', 'staff')
            ->select('r.name')
            ->getQuery()
            ->getArrayResult();

        foreach($results as $type) {

            if (!in_array($type, $types))
                $types[$type['name']] = 'html://';
        }
        $value = [];

        $resolver = new OptionsResolver();
        $resolver->setDefaults($types);
        $value = $resolver->resolve($value);

        $setting = $sm->createOneByName('staff.application_form_reference_links');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('displayName', 'Staff Application Reference Links')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue($value)
            ->__set('translateChoice', null)
            ->__set('description', "Link to an external form that will be emailed to a referee of the applicant's choosing.");
        if (empty($setting->getValue())) {
            $setting->setValue($value);
        }
        $settings[] = $setting;

        $section['name'] = 'Application Form Referee Documents';
        $section['description'] = 'Add an html link for each role as required.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $this->sections = $sections;
        $settings = [];

        $setting = $sm->createOneByName('staff.application_form_required_documents');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('displayName', 'Staff Application Required Documents')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(['Curriculum Vitae'])
            ->__set('translateChoice', null)
            ->__set('description', "List of documents which must be submitted electronically with the application form.");
        if (empty($setting->getValue())) {
            $setting->setValue(['Curriculum Vitae']);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_required_documents_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Required Documents Text')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue('<p>Please submit the following document(s) to ensure your application can be processed without delay.</p>')
            ->__set('translateChoice', null)
            ->__set('description', "List of documents which must be submitted electronically with the application form.");
        if (empty($setting->getValue())) {
            $setting->setValue('<p>Please submit the following document(s) to ensure your application can be processed without delay.</p>');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_required_documents_compulsory');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('displayName', 'Staff Application Required Documents Compulsory')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ->__set('description', "Are the required documents compulsory?");
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $section['name'] = 'Required Documents Options';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $this->sections = $sections;
        $settings = [];

        $setting = $sm->createOneByName('staff.application_form_username_format');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->__set('displayName', 'Staff User-name Format')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue('[preferredNameInitial].[surname]')
            ->__set('translateChoice', null)
            ->__set('description', "How should user-names be formatted? Choose from [preferredName], [preferredNameInitial], [surname].");
        if (empty($setting->getValue())) {
            $setting->setValue('[preferredNameInitial].[surname]');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_notification_message');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Staff Application Notification Message')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', null)
            ->__set('description', "A custom message to add to the standard email on acceptance.");
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_notification_default');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('displayName', 'Staff Application Notification Default')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', null)
            ->__set('description', "Should acceptance email be turned on or off by default.");
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_default_email');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->__set('displayName', 'Staff Default Email')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', null)
            ->__set('description', "Set default email on acceptance, using [username] to insert user-name.  e.g. [username]_staff@your-domain.com");
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('staff.application_form_default_website');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('text')
            ->__set('displayName', 'Staff Default Website')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', null)
            ->__set('description', "Set default website on acceptance, using [username] to insert user-name.  e.g. http://your-domain.com/[username]/");
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $section['name'] = 'Acceptance Options';
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