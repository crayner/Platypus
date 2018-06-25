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
     * @return array
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('finance.email');

        $setting->setName('finance.email')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('email')
            ->__set('displayName', 'Email')
            ->__set('description', 'Send messages from this email address.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators([
                    new Email(),
                ])
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.online_payment_enabled');

        $setting->setName('finance.online_payment_enabled')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Online Payment')
            ->__set('description', 'Should invoices be payable online, via an encrypted link in the invoice? Requires correctly configured payment gateway in System Settings.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $setting->setHideParent('finance.online_payment_enabled');
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.online_payment_threshold');

        $setting->setName('finance.online_payment_threshold')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('currency')
            ->__set('displayName', 'Online Payment Threshold')
            ->__set('description', 'If invoices are payable online, what is the maximum payment allowed? Useful for controlling payment fees. No value means unlimited. In {{setting.currency}}');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $setting->setHideParent('finance.online_payment_enabled');
        $settings[] = $setting;

        $section['name'] = 'General Settings';
        $section['description'] = 'Some settings may be hidden with the choices made for your school.';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_text');

        $setting->setName('finance.invoice_text')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Invoice Text')
            ->__set('description', 'Text to appear in invoice, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_notes');

        $setting->setName('finance.invoice_notes')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Invoice Notes')
            ->__set('description', 'Text to appear in invoice, below invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoicee_name_style');

        $setting->setName('finance.invoicee_name_style')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Invoicee Name Style')
            ->__set('description', 'Determines how invoicee name appears on invoices and receipts.');
        if (empty($setting->getValue())) {
            $setting->setValue('surname_preferred_name')
                ->__set('choice', ['surname_preferred_name,official_name'])
                ->setValidators(null)
                ->setDefaultValue('surname_preferred_name')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.invoice_number');

        $setting->setName('finance.invoice_number')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Invoice Number Style')
            ->__set('description', 'How should invoice numbers be constructed?');
        if (empty($setting->getValue())) {
            $setting->setValue('invoice_id')
                ->__set('choice', ['invoice_id,person_id_invoice_id,student_id_invoice_id'])
                ->setValidators(null)
                ->setDefaultValue('invoice_id')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Invoices';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.receipt_text');

        $setting->setName('finance.receipt_text')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Receipt Text')
            ->__set('description', 'Text to appear in receipt, above receipt details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.receipt_notes');

        $setting->setName('finance.receipt_notes')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Receipt Notes')
            ->__set('description', 'Text to appear in receipt, below receipt details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.hide_itemisation');

        $setting->setName('finance.hide_itemisation')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Hide Itemisation')
            ->__set('description', 'Hide fee and payment details in receipts?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Receipts';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.reminder_1_text');

        $setting->setName('finance.reminder_1_text')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Reminder 1 Text')
            ->__set('description', 'Text to appear in first level reminder level, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.reminder_2_text');

        $setting->setName('finance.reminder_2_text')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
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

        $setting = $sm->createOneByName('finance.reminder_3_text');

        $setting->setName('finance.reminder_3_text')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('text')
            ->__set('displayName', 'Reminder 3 Text')
            ->__set('description', 'Text to appear in third level reminder level, above invoice details and fees.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Reminders';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('finance.budget_categories');

        $setting->setName('finance.budget_categories')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Budget Categories')
            ->__set('description', 'List of budget categories.');
        if (empty($setting->getValue())) {
            $setting->setValue(['academic','administration','capital'])
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(['academic','administration','capital'])
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.expense_approval_type');

        $setting->setName('finance.expense_approval_type')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Expense Approval Type')
            ->__set('description', 'How should expense approval be dealt with?');
        if (empty($setting->getValue())) {
            $setting->setValue('one_of')
                ->__set('choice', ['one_of,two_of,chain_of_all'])
                ->setValidators(null)
                ->setDefaultValue('one_of')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.budget_level_expense_approval');

        $setting->setName('finance.budget_level_expense_approval')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Budget Level Expense Approval')
            ->__set('description', 'Should approval from a budget member with Full access be required?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.expense_request_template');

        $setting->setName('finance.expense_request_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Expense Request Template')
            ->__set('description', 'An HTML template to be used in the description field of expense requests.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.allow_expense_add');

        $setting->setName('finance.allow_expense_add')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Allow Expense Add')
            ->__set('description', 'Allows privileged users to add expenses without going through request process.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.purchasing_officer');

        $setting->setName('finance.purchasing_officer')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Purchasing Officer')
            ->__set('description', 'Staff member responsible for purchasing for the school.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', ['do this list..,  Needs Staff table defined.'])
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('finance.reimbursement_officer');

        $setting->setName('finance.reimbursement_officer')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('choice')
            ->__set('displayName', 'Reimbursement Officer')
            ->__set('description', 'Staff member responsible for reimbursing expenses.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', ['do this list..,  Needs Staff table defined.'])
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'School');
        }
        $settings[] = $setting;

        $section['name'] = 'Expenses';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_finance_settings';

        return $sections;
    }
}
