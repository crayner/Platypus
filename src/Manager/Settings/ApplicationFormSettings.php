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
 * Time: 15:08
 */
namespace App\Manager\Settings;

use App\Entity\SchoolYear;
use App\Manager\MessageManager;
use App\Manager\SchoolYearManager;
use App\Manager\SettingManager;
use Doctrine\DBAL\Connection;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Class ApplicationFormSettings
 * @package App\Manager\Settings
 */
class ApplicationFormSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ApplicationForm.orm.yml';
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

        $setting = $sm->createOneByName('application_form.introduction');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Form Introduction.')
            ->__set('choice', null)
            ->__set('description', 'Information to display before the form');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.application_form_referee_link');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('url')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Form Referee Link.')
            ->__set('choice', null)
            ->__set('description', 'Link to an external form that will be emailed to a referee of the applicant\'s choosing.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.postscript');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Form Postscript.')
            ->__set('choice', null)
            ->__set('description', 'Information to display at the end of the form');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.agreement');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Form Agreement.')
            ->__set('choice', null)
            ->__set('description', 'Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.application_fee');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('string')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Fee.')
            ->__set('choice', null)
            ->__set('description', sprintf('The cost of applying to the school. In %s.', $sm->get('currency')));
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.public_applications');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Public Applications?')
            ->__set('choice', null)
            ->__set('description', 'If yes, members of the public can submit applications');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.milestones');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDefaultValue([])
            ->__set('translateChoice', null)
            ->__set('displayName', 'Application Milestones')
            ->__set('choice', null)
            ->__set('description', 'List of the major steps in the application process. Applicants can be tracked through the various stages.');
        if (empty($setting->getValue())) {
            $setting->setValue([])
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.how_did_you_hear');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDefaultValue([])
            ->__set('translateChoice', null)
            ->__set('displayName', 'How Did Your Hear?')
            ->__set('choice', null)
            ->__set('description', '');
        if (empty($setting->getValue())) {
            $setting->setValue(['Advertisement','Personal Recommendation','World Wide Web','Others'])
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.enable_limited_years_of_entry');

        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue([])
            ->__set('translateChoice', null)
            ->__set('displayName', 'Enable Limited Years of Entry')
            ->__set('choice', null)
            ->__set('description', 'If yes, applicants choices for Year of Entry can be limited to specific school years.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Advertisement','Personal Recommendation','World Wide Web','Others'])
            ;
        }
        $setting->setHideParent('application_form.enable_limited_years_of_entry');
        $settings[] = $setting;

        $results = $sm->getEntityManager()->getRepository(SchoolYear::class)->createQueryBuilder('y')
            ->select('y.name, y.id')
            ->where('y.status IN (:statusList)')
            ->setParameter('statusList', ['current','upcoming'], Connection::PARAM_STR_ARRAY)
            ->orderBy('y.firstDay')
            ->getQuery()
            ->getArrayResult();
        $years = [];
        foreach($results as $year)
            $years[$year['name']] = $year['id'];

        $setting = $sm->createOneByName('application_form.available_years_of_entry');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('multiChoice')
            ->setValidators(null)
            ->setDefaultValue([])
            ->__set('translateChoice', false)
            ->__set('displayName', 'Available Years of Entry')
            ->__set('choice', $years)
            ->__set('description', 'Which school years should be available to apply to? Use Control, Command and/or Shift to select multiple.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('application_form.enable_limited_years_of_entry');
        $settings[] = $setting;

        $section['name'] = 'General Options';
        $section['description'] = '';
        $section['colour'] = 'primary';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('application_form.required_documents');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Required Documents')
            ->__set('choice', null)
            ->__set('description', 'List of documents which must be submitted electronically with the application form.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.internal_documents');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Internal Documents')
            ->__set('choice', null)
            ->__set('description', 'List of documents for internal upload and use.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.required_documents_text');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Required Documents Text')
            ->__set('choice', null)
            ->__set('description', 'Explanatory text to appear with the required documents?');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.required_documents_compulsory');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Required Documents Compulsory?')
            ->__set('choice', null)
            ->__set('description', 'Explanatory text to appear with the required documents?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $section['name'] = 'Required Document Options';
        $section['description'] = '';
        $section['colour'] = 'primary';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('application_form.language_options_active');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Language Options Active')
            ->__set('choice', null)
            ->__set('description', 'Should the Language Options section be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $setting->setHideParent('application_form.language_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.language_options_blurb');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Language Options Introduction')
            ->__set('choice', null)
            ->__set('description', 'Introductory text if Language Options section is turned on.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('application_form.language_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.language_options_language_list');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
            ->setDefaultValue([])
            ->__set('translateChoice', false)
            ->__set('displayName', 'Language Options Language List')
            ->__set('choice', null)
            ->__set('description', 'List of available language selections if Language Options section is turned on.');
        if (empty($setting->getValue())) {
            $setting->setValue([]);
        }
        $setting->setHideParent('application_form.language_options_active');
        $settings[] = $setting;

        $section['name'] = 'Language Learning Options';
        $section['description'] = 'Set values for applicants to specify which language they wish to learn.';
        $section['colour'] = 'primary';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('application_form.sen_options_active');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Special Education Needs Active')
            ->__set('choice', null)
            ->__set('description', 'Should the Special Education Needs section be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $setting->setHideParent('application_form.sens_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('students.application_form_sentext');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue("<p>Please indicate whether or not your child has any known, or suspected, special educational needs, or whether they have been assessed for any such needs in the past. Provide any comments or information concerning your child's development that may be relevant to your child's performance in the classroom or elsewhere? Incorrect or withheld information may affect continued enrolment.</p>")
            ->__set('translateChoice', false)
            ->__set('displayName', 'Special Education Application Text')
            ->__set('choice', null)
            ->__set('description', 'Text to appear with the Special Educational Needs section of the student application form.');
        if (empty($setting->getValue())) {
            $setting->setValue("<p>Please indicate whether or not your child has any known, or suspected, special educational needs, or whether they have been assessed for any such needs in the past. Provide any comments or information concerning your child's development that may be relevant to your child's performance in the classroom or elsewhere? Incorrect or withheld information may affect continued enrolment.</p>");
        }
        $setting->setHideParent('application_form.sens_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.scholarship_options_active');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Scholarship Options Active')
            ->__set('choice', null)
            ->__set('description', 'Should the Scholarship Options section be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $setting->setHideParent('application_form.scholarship_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.scholarships');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Scholarships ')
            ->__set('choice', null)
            ->__set('description', 'Information to display before the scholarship options.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('application_form.scholarship_options_active');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.payment_options_active');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Payment Options Active')
            ->__set('choice', null)
            ->__set('description', 'Should the Payment section be turned on?');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $section['name'] = 'Student Sections';
        $section['description'] = '';
        $section['colour'] = 'primary';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];

        $setting = $sm->createOneByName('application_form.username_format');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('text')
            ->setValidators(null)
            ->setDefaultValue('[preferredNameInitial]. [surname]')
            ->__set('translateChoice', false)
            ->__set('displayName', 'User-name Format')
            ->__set('choice', null)
            ->__set('description', 'How should user-names be formatted? Choose from [preferredName], [preferredNameInitial], [surname].');
        if (empty($setting->getValue())) {
            $setting->setValue('[preferredNameInitial]. [surname]');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.notification_student_default');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Student Notification Default')
            ->__set('choice', null)
            ->__set('description', 'Should student acceptance email be turned on or off.');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $setting->setHideParent('application_form.notification_student_default');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.notification_student_message');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Student Notification Message')
            ->__set('choice', null)
            ->__set('description', 'A custom message to add to the standard email to students on acceptance.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('application_form.notification_student_default');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.notification_parents_default');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Parent Notification Default')
            ->__set('choice', null)
            ->__set('description', 'Should parent acceptance email be turned on or off.');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $setting->setHideParent('application_form.notification_parents_default');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.notification_parents_message');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Parent Notification Message')
            ->__set('choice', null)
            ->__set('description', 'A custom message to add to the standard email to parents on acceptance.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $setting->setHideParent('application_form.notification_parents_default');
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.student_default_email');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('string')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Student Default Email')
            ->__set('choice', null)
            ->__set('description', 'Set default email for students on acceptance, using [username] to insert user-name.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.student_default_website');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('url')
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Student Default Website')
            ->__set('choice', null)
            ->__set('description', 'Set default website for students on acceptance, using [username] to insert user-name.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.auto_house_assign');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('boolean')
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', false)
            ->__set('displayName', 'Auto House Assign')
            ->__set('choice', null)
            ->__set('description', 'Attempt to automatically place student in a house?');
        if (empty($setting->getValue())) {
            $setting->setValue(false);
        }
        $settings[] = $setting;

        $section['name'] = 'Acceptance Options';
        $section['description'] = '';
        $section['colour'] = 'primary';
        $section['settings'] = $settings;

        $sections[] = $section;

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
}
