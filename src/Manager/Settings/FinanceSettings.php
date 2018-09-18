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
 * Date: 21/06/2018
 * Time: 14:55
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Manager\StaffManager;
use Hillrange\Form\Validator\Colour;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Class FinanceSettings
 * @package App\Manager\Settings
 */
class FinanceSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Finance';
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

        $setting = $sm->createOneByName('finance.email');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('email')
            ->__set('choice', null)
            ->setValidators([
                new Email(),
            ])
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Email')
            ->__set('description', 'Send messages from this email address.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.finance_online_payment_enabled');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Online Payment')
            ->__set('description', 'Should invoices be payable online, via an encrypted link in the invoice? Requires correctly configured payment gateway in System Settings.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $setting->setHideParent('finance.finance_online_payment_enabled');
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.finance_online_payment_threshold');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('currency')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Online Payment Threshold')
            ->__set('description', 'If invoices are payable online, what is the maximum payment allowed? Useful for controlling payment fees. No value means unlimited. In {{setting.currency}}');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $setting->setHideParent('finance.finance_online_payment_enabled');
        $settings[] = $setting;

        $section['name'] = 'General Settings';
        $section['description'] = 'Some settings may be hidden with the choices made for your school.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Invoice Text')
            ->__set('description', 'Text to appear in invoice, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_notes');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Invoice Notes')
            ->__set('description', 'Text to appear in invoice, below invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoicee_name_style');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['surname_preferred_name','official_name'])
            ->setValidators(null)
            ->setDefaultValue('surname_preferred_name')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Invoicee Name Style')
            ->__set('description', 'Determines how invoicee name appears on invoices and receipts.');
        if (empty($setting->getValue())) {
            $setting->setValue('surname_preferred_name')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_number');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['invoice_id','person_id_invoice_id','student_id_invoice_id'])
            ->setValidators(null)
            ->setDefaultValue('invoice_id')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Invoice Number Style')
            ->__set('description', 'How should invoice numbers be constructed?');
        if (empty($setting->getValue())) {
            $setting->setValue('invoice_id')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Invoices';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.receipt_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Receipt Text')
            ->__set('description', 'Text to appear in receipt, above receipt details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.receipt_notes');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Receipt Notes')
            ->__set('description', 'Text to appear in receipt, below receipt details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.hide_itemisation');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Hide Itemisation')
            ->__set('description', 'Hide fee and payment details in receipts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Receipts';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.reminder1_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Reminder 1 Text')
            ->__set('description', 'Text to appear in first level reminder level, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.reminder2_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('displayName', 'Reminder 2 Text')
            ->__set('description', 'Text to appear in second level reminder level, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.reminder3_text');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Reminder 3 Text')
            ->__set('description', 'Text to appear in third level reminder level, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Reminders';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.budget_categories');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(['academic','administration','capital'])
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Budget Categories')
            ->__set('description', 'List of budget categories.');
        if (empty($setting->getValue())) {
            $setting->setValue(['academic','administration','capital'])
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.expense_approval_type');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->__set('choice', ['one_of','two_of','chain_of_all'])
            ->setValidators(null)
            ->setDefaultValue('one_of')
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Expense Approval Type')
            ->__set('description', 'How should expense approval be dealt with?');
        if (empty($setting->getValue())) {
            $setting->setValue('one_of')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.budget_level_expense_approval');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Budget Level Expense Approval')
            ->__set('description', 'Should approval from a budget member with Full access be required?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.expense_request_template');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('html')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Expense Request Template')
            ->__set('description', 'An HTML template to be used in the description field of expense requests.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.allow_expense_add');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Allow Expense Add')
            ->__set('description', 'Allows privileged users to add expenses without going through request process.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.purchasing_officer');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Purchasing Officer')
            ->__set('choice', array_flip(StaffManager::getStaffListChoice()))
            ->__set('description', 'Staff member responsible for purchasing for the school.');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.reimbursement_officer');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('choice')
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', null)
            ->__set('displayName', 'Reimbursement Officer')
            ->__set('choice', array_flip(StaffManager::getStaffListChoice()))
            ->__set('description', 'Staff member responsible for reimbursing expenses.');
        if (empty($setting->getValue())) {
            $setting->setValue(true);
        }
        $settings[] = $setting;

        $section['name'] = 'Expenses';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_finance_settings';

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
