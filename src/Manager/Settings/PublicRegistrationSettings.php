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
 * Date: 16/09/2018
 * Time: 15:42
 */
namespace App\Manager\Settings;

use App\Manager\MessageManager;
use App\Manager\PersonRoleManager;
use App\Manager\SettingManager;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class PublicRegistrationSettings
 * @package App\Manager\Settings
 */
class PublicRegistrationSettings extends SettingCreationManager
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'PublicRegistration';
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
        $this->setSectionsHeader('public_registration_settings');

        $setting = $sm->createOneByName('person_admin.enable_public_registration')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Public Registration')
            ->setDescription('Allows members of the public to register to use the system.');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('person_admin.public_registration_minimum_age')
            ->setSettingType('number')
            ->setValidators([
                new Range(['max' => 30, 'min' => 5])
            ])
            ->setDisplayName('Public Registration Minimum Age')
            ->setDescription('The minimum age, in years, permitted to register.');
        if (empty($setting->getValue()))
            $setting->setValue(13);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('person_admin.public_registration_default_status')
            ->setSettingType('enum')
            ->setDisplayName('Public Registration Default Status')
            ->setChoices([
                'person_admin.public_registration_default_status.full' => 'full',
                'person_admin.public_registration_default_status.pending' => 'pending'
            ])
           ->setDescription('Should new people be \'Full\' or \'Pending Approval\'?');
        if (! in_array($setting->getValue(),['full', 'pending']))
            $setting->setValue('pending');
        $this->addSetting($setting, []);

        $prm = new PersonRoleManager($sm->getEntityManager(), new MessageManager());

        $setting = $sm->createOneByName('person_admin.public_registration_default_role')
            ->setSettingType('enum')
            ->setDisplayName('Public Registration Default Role')
            ->setChoices($prm->getPersonRoleList())
            ->setDescription('System role to be assigned to registering members of the public.');
        if (empty($setting->getValue()))
            $setting->setValue('student');
        $this->addSetting($setting, []);

        $this->addSection('General Settings');

        $setting = $sm->createOneByName('person_admin.public_registration_intro')
            ->setSettingType('html')
            ->setDisplayName('Public Registration Introductory Text')
            ->setDescription('HTML text that will appear above the public registration form.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('person_admin.public_registration_postscript')
            ->setSettingType('html')
            ->setDisplayName('Public Registration Postscript')
            ->setDescription('HTML text that will appear underneath the public registration form.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('person_admin.public_registration_privacy_statement')
            ->setSettingType('html')
            ->setDisplayName('Public Registration Privacy Statement')
            ->setDescription('HTML text that will appear above the Submit button, explaining privacy policy.');
        if (empty($setting->getValue()))
            $setting->setValue('<p>By registering for this site you are giving permission for your personal data to be used and shared within this organisation and its websites. We will not share your personal data outside our organisation.</p>');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('person_admin.public_registration_agreement')
            ->setSettingType('html')
            ->setDisplayName('Public Registration Agreement')
            ->setDescription('Agreement that people must confirm before joining. Blank for no agreement.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Interface Options');

        $this->setSettingManager(null);

        return $this;
    }
}
