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
class StaffApplicationFormSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);
        $this->setSectionsHeader('application_form_settings');

        $setting = $sm->createOneByName('staff.application_form_introduction')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Introduction')
            ->setDescription('Information to display before the form');
        if (empty($setting->getValue()));
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_questions')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Questions')
            ->setDescription('HTML text that will appear as questions for the applicant to answer.');
        if (empty($setting->getValue()))
            $setting->setValue("<span style='text-decoration: underline; font-weight: bold'>Why are you applying for this role?</span><p></p>");
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_postscript')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Postscript')
            ->setDescription('Information to display at the end of the form.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_agreement')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Agreement')
            ->setDescription('Without this text, which is displayed above the agreement, peoples will not be asked to agree to anything.');
        if (empty($setting->getValue()))
            $setting->setValue('<p>In submitting this form, I confirm that all information provided above is accurate and complete to the best of my knowledge.</p>');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_public_applications')
            ->setSettingType('boolean')
            ->setDisplayName('Staff Public Applications?')
            ->setDescription('If yes, members of the public can submit staff applications.');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_milestones')
            ->setSettingType('array')
            ->setDisplayName('Staff Application Milestones')
            ->setDescription('List of the major steps in the application process. Applicants can be tracked through the various stages.');
        if (empty($setting->getValue()))
            $setting->setValue(['Short List','First Interview','Second Interview','Offer Made','Offer Accepted','Contact Issued','Contact Signed']);
        $this->addSetting($setting, []);

        $this->addSection('General options');

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

        $setting = $sm->createOneByName('staff.application_form_reference_links')
            ->setSettingType('array')
            ->setDisplayName('Staff Application Reference Links')
            ->setDescription("Link to an external form that will be emailed to a referee of the applicant's choosing.");
        if (empty($setting->getValue()))
            $setting->setValue($value);
        $this->addSetting($setting, []);

        $this->addSection('Application Form Referee Documents', 'Add an html link for each role as required.');

        $setting = $sm->createOneByName('staff.application_form_required_documents')
            ->setSettingType('array')
            ->setDisplayName('Staff Application Required Documents')
            ->setDescription("List of documents which must be submitted electronically with the application form.");
        if (empty($setting->getValue()))
            $setting->setValue(['Curriculum Vitae']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_required_documents_text')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Required Documents Text')
            ->setDescription("List of documents which must be submitted electronically with the application form.");
        if (empty($setting->getValue()))
            $setting->setValue('<p>Please submit the following document(s) to ensure your application can be processed without delay.</p>');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_required_documents_compulsory')
            ->setSettingType('boolean')
            ->setDisplayName('Staff Application Required Documents Compulsory')
            ->setDescription("Are the required documents compulsory?");
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $this->addSection('Required Documents Options');

        $setting = $sm->createOneByName('staff.application_form_username_format')
            ->setSettingType('twig')
            ->setDisplayName('Staff User-name Format')
            ->setDescription("How should user-names be formatted? Choose from [preferredName], [preferredNameInitial], [surname].");
        if (empty($setting->getValue()))
            $setting->setValue('{{ preferredNameInitial}}.{{ surname }}');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_notification_message')
            ->setSettingType('html')
            ->setDisplayName('Staff Application Notification Message')
            ->setDescription("A custom message to add to the standard email on acceptance.");
        if (empty($setting->getValue()))
            $setting->setValue('');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_notification_default')
            ->setSettingType('boolean')
            ->setDisplayName('Staff Application Notification Default')
            ->setDescription("Should acceptance email be turned on or off by default.");
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_default_email')
            ->setSettingType('twig')
            ->setDisplayName('Staff Default Email')
            ->setDescription("Set default email on acceptance, using {{ username }} to insert user-name.  e.g. {{ username }}_staff@your-domain.com");
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('staff.application_form_default_website')
            ->setSettingType('twig')
            ->setDisplayName('Staff Default Website')

            ->setValidators(null)
           ->setDescription("Set default website on acceptance, using {{ username }} to insert user-name.  e.g. http://your-domain.com/{{ username }}/");
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Acceptance Options');

        $this->setSettingManager(null);

        return $this;
    }
}
