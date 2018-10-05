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
class ApplicationFormSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'ApplicationForm';
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
        $this->setSectionsHeader('application_form_settings');

        $setting = $sm->createOneByName('application_form.introduction')
            ->setSettingType('html')
            ->setDisplayName('Application Form Introduction.')
            ->setDescription('Information to display before the form');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('students.application_form_referee_link')
            ->setSettingType('url')
            ->setDisplayName('Application Form Referee Link.')
            ->setDescription('Link to an external form that will be emailed to a referee of the applicant\'s choosing.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.postscript')
            ->setSettingType('html')
            ->setDisplayName('Application Form Postscript.')
            ->setDescription('Information to display at the end of the form');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.agreement')
            ->setSettingType('html')
            ->setDisplayName('Application Form Agreement.')
            ->setDescription('Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.application_fee')
            ->setSettingType('string')
            ->setDisplayName('Application Fee.')
            ->setDescription(sprintf('The cost of applying to the school. In %s.', $sm->get('currency')));
        if (empty($setting->getValue()))
            $setting->setValue('0');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.public_applications')
            ->setSettingType('boolean')
            ->setDisplayName('Public Applications?')
            ->setDescription('If yes, members of the public can submit applications');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.milestones')
            ->setSettingType('array')
            ->setDisplayName('Application Milestones')
            ->setDescription('List of the major steps in the application process. Applicants can be tracked through the various stages.');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.how_did_you_hear')
            ->setSettingType('array')
            ->setDisplayName('How Did Your Hear?')
            ->setDescription('');
        if (empty($setting->getValue()))
            $setting->setValue(['Advertisement','Personal Recommendation','World Wide Web','Others']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.enable_limited_years_of_entry')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Limited Years of Entry')
            ->setDescription('If yes, applicants choices for Year of Entry can be limited to specific school years.');
        if (empty($setting->getValue()))
            $setting->setValue(['Advertisement','Personal Recommendation','World Wide Web','Others']);
        $this->addSetting($setting, ['hideParent' => 'application_form.enable_limited_years_of_entry']);

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

        $setting = $sm->createOneByName('application_form.available_years_of_entry')
            ->setSettingType('multiEnum')
            ->setDisplayName('Available Years of Entry')
            ->setChoices($years)
            ->setDescription('Which school years should be available to apply to? Use Control, Command and/or Shift to select multiple.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.enable_limited_years_of_entry']);

        $this->addSection('General Options', '', ['colour' => 'primary']);

        $setting = $sm->createOneByName('application_form.required_documents')
            ->setSettingType('array')
            ->setDisplayName('Required Documents')
            ->setDescription('List of documents which must be submitted electronically with the application form.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.internal_documents')
            ->setSettingType('array')
            ->setDisplayName('Internal Documents')
            ->setDescription('List of documents for internal upload and use.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.required_documents_text')
            ->setSettingType('html')
            ->setDisplayName('Required Documents Text')
            ->setDescription('Explanatory text to appear with the required documents?');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.required_documents_compulsory')
            ->setSettingType('boolean')
            ->setDisplayName('Required Documents Compulsory?')
            ->setDescription('Explanatory text to appear with the required documents?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Required Document Options','',['colour' => 'primary']);

        $setting = $sm->createOneByName('application_form.language_options_active')
            ->setSettingType('boolean')
            ->setDisplayName('Language Options Active')
            ->setDescription('Should the Language Options section be turned on?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, ['hideParent' => 'application_form.language_options_active']);

        $setting = $sm->createOneByName('application_form.language_options_blurb')
            ->setSettingType('html')
            ->setDisplayName('Language Options Introduction')
            ->setDescription('Introductory text if Language Options section is turned on.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.language_options_active']);

        $setting = $sm->createOneByName('application_form.language_options_language_list')
            ->setSettingType('array')
            ->setDisplayName('Language Options Language List')
            ->setDescription('List of available language selections if Language Options section is turned on.');
        if (empty($setting->getValue()))
            $setting->setValue([]);
        $this->addSetting($setting, ['hideParent' => 'application_form.language_options_active']);

        $this->addSection('Language Learning Options','Set values for applicants to specify which language they wish to learn.',['colour' => 'primary']);

        $setting = $sm->createOneByName('application_form.sen_options_active')
            ->setSettingType('boolean')
            ->setDisplayName('Special Education Needs Active')
            ->setDescription('Should the Special Education Needs section be turned on?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, ['hideParent' => 'application_form.sen_options_active']);

        $setting = $sm->createOneByName('students.application_form_sentext')
            ->setSettingType('html')
            ->setDisplayName('Special Education Application Text')
            ->setDescription('Text to appear with the Special Educational Needs section of the student application form.');
        if (empty($setting->getValue()))
            $setting->setValue("<p>Please indicate whether or not your child has any known, or suspected, special educational needs, or whether they have been assessed for any such needs in the past. Provide any comments or information concerning your child's development that may be relevant to your child's performance in the classroom or elsewhere? Incorrect or withheld information may affect continued enrolment.</p>");
        $this->addSetting($setting, ['hideParent' => 'application_form.sen_options_active']);

        $setting = $sm->createOneByName('application_form.scholarship_options_active')
            ->setSettingType('boolean')
            ->setDisplayName('Scholarship Options Active')
            ->setDescription('Should the Scholarship Options section be turned on?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, ['hideParent' => 'application_form.sen_options_active']);

        $setting = $sm->createOneByName('application_form.scholarships')
            ->setSettingType('html')
            ->setDisplayName('Scholarships ')
            ->setDescription('Information to display before the scholarship options.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.sen_options_active']);

        $setting = $sm->createOneByName('application_form.payment_options_active')
            ->setSettingType('boolean')
            ->setDisplayName('Payment Options Active')
            ->setDescription('Should the Payment section be turned on?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $this->addSection('Student Sections', '', ['colour' => 'primary']);

        $setting = $sm->createOneByName('application_form.username_format')
            ->setSettingType('twig')
            ->setDisplayName('User-name Format')
            ->setDescription('How should user-names be formatted? Choose from [preferredName], [preferredNameInitial], [surname].');
        if (empty($setting->getValue()))
            $setting->setValue('{{ preferredNameInitial }}. {{ surname }}');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.notification_student_default')
            ->setSettingType('boolean')
            ->setDisplayName('Student Notification Default')
            ->setDescription('Should student acceptance email be turned on or off.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'application_form.notification_student_default']);

        $setting = $sm->createOneByName('application_form.notification_student_message')
            ->setSettingType('html')
            ->setDisplayName('Student Notification Message')
            ->setDescription('A custom message to add to the standard email to students on acceptance.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.notification_student_default']);

        $setting = $sm->createOneByName('application_form.notification_parents_default')
            ->setSettingType('boolean')
            ->setDisplayName('Parent Notification Default')
            ->setDescription('Should parent acceptance email be turned on or off.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'application_form.notification_parents_default']);

        $setting = $sm->createOneByName('application_form.notification_parents_message')
            ->setSettingType('html')
            ->setDisplayName('Parent Notification Message')
            ->setDescription('A custom message to add to the standard email to parents on acceptance.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.notification_parents_default']);

        $setting = $sm->createOneByName('application_form.student_default_email')
            ->setSettingType('twig')
            ->setDisplayName('Student Default Email')
            ->setDescription("Set default email on acceptance, using {{ username }} to insert user-name.  e.g. {{ username }}_student@your-domain.com");
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['hideParent' => 'application_form.notification_parents_default']);

        $setting = $sm->createOneByName('application_form.student_default_website')
            ->setSettingType('twig')
            ->setDisplayName('Student Default Website')
            ->setDescription("Set default website on acceptance, using {{ username }} to insert user-name.  e.g. http://your-domain.com/{{ username }}/");
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('application_form.auto_house_assign')
            ->setSettingType('boolean')
            ->setDisplayName('Auto House Assign')
            ->setDescription('Attempt to automatically place student in a house?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Acceptance Options', '', ['colour' => 'primary']);

        $this->setSettingManager(null);

        return $this;
    }
}
