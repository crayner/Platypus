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
            ->setDisplayName('Application Form Introduction.')

           ->setDescription('Information to display before the form');
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
            ->setDisplayName('Application Form Referee Link.')

           ->setDescription('Link to an external form that will be emailed to a referee of the applicant\'s choosing.');
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
            ->setDisplayName('Application Form Postscript.')

           ->setDescription('Information to display at the end of the form');
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
            ->setDisplayName('Application Form Agreement.')

           ->setDescription('Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything');
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
            ->setDisplayName('Application Fee.')

           ->setDescription(sprintf('The cost of applying to the school. In %s.', $sm->get('currency')));
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
            ->setDisplayName('Public Applications?')

           ->setDescription('If yes, members of the public can submit applications');
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
            ->setDisplayName('Application Milestones')

           ->setDescription('List of the major steps in the application process. Applicants can be tracked through the various stages.');
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
            ->setDisplayName('How Did Your Hear?')

           ->setDescription('');
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
            ->setDisplayName('Enable Limited Years of Entry')

           ->setDescription('If yes, applicants choices for Year of Entry can be limited to specific school years.');
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
            ->setDisplayName('Available Years of Entry')
            ->__set('choice', $years)
           ->setDescription('Which school years should be available to apply to? Use Control, Command and/or Shift to select multiple.');
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
            ->setDisplayName('Required Documents')

           ->setDescription('List of documents which must be submitted electronically with the application form.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.internal_documents');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('array')
            ->setValidators(null)
                ->setDisplayName('Internal Documents')

           ->setDescription('List of documents for internal upload and use.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.required_documents_text');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('html')
            ->setValidators(null)
             ->setDisplayName('Required Documents Text')

           ->setDescription('Explanatory text to appear with the required documents?');
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
             ->setDisplayName('Required Documents Compulsory?')

           ->setDescription('Explanatory text to appear with the required documents?');
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
             ->setDisplayName('Language Options Active')

           ->setDescription('Should the Language Options section be turned on?');
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
             ->setDisplayName('Language Options Introduction')

           ->setDescription('Introductory text if Language Options section is turned on.');
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
             ->setDisplayName('Language Options Language List')

           ->setDescription('List of available language selections if Language Options section is turned on.');
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
             ->setDisplayName('Special Education Needs Active')

           ->setDescription('Should the Special Education Needs section be turned on?');
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
             ->setDisplayName('Special Education Application Text')

           ->setDescription('Text to appear with the Special Educational Needs section of the student application form.');
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
             ->setDisplayName('Scholarship Options Active')

           ->setDescription('Should the Scholarship Options section be turned on?');
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
             ->setDisplayName('Scholarships ')

           ->setDescription('Information to display before the scholarship options.');
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
             ->setDisplayName('Payment Options Active')

           ->setDescription('Should the Payment section be turned on?');
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
             ->setDisplayName('User-name Format')

           ->setDescription('How should user-names be formatted? Choose from [preferredName], [preferredNameInitial], [surname].');
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
             ->setDisplayName('Student Notification Default')

           ->setDescription('Should student acceptance email be turned on or off.');
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
             ->setDisplayName('Student Notification Message')

           ->setDescription('A custom message to add to the standard email to students on acceptance.');
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
             ->setDisplayName('Parent Notification Default')

           ->setDescription('Should parent acceptance email be turned on or off.');
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
             ->setDisplayName('Parent Notification Message')

           ->setDescription('A custom message to add to the standard email to parents on acceptance.');
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
             ->setDisplayName('Student Default Email')

           ->setDescription('Set default email for students on acceptance, using [username] to insert user-name.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('application_form.student_default_website');
        $setting
            ->__set('role', 'ROLE_ADMIN')
            ->setSettingType('url')
            ->setValidators(null)
             ->setDisplayName('Student Default Website')

           ->setDescription('Set default website for students on acceptance, using [username] to insert user-name.');
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
             ->setDisplayName('Auto House Assign')

           ->setDescription('Attempt to automatically place student in a house?');
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
