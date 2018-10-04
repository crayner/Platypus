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
class FinanceSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('finance.email')
            ->setSettingType('email')
            ->setValidators([
                new Email(),
            ])
            ->setDisplayName('Email')
            ->setDescription('Send messages from this email address.');
        if (empty($setting->getValue())) 
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.finance_online_payment_enabled')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Online Payment')
            ->setDescription('Should invoices be payable online, via an encrypted link in the invoice? Requires correctly configured payment gateway in System Settings.');
        if (empty($setting->getValue())) 
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'finance.finance_online_payment_enabled']);

        $setting = $sm->createOneByName('finance.finance_online_payment_threshold')
            ->setSettingType('currency')
            ->setDisplayName('Online Payment Threshold')
            ->setDescription('If invoices are payable online, what is the maximum payment allowed? Useful for controlling payment fees. No value means unlimited. In {{setting.currency}}');
        if (empty($setting->getValue())) 
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'finance.finance_online_payment_enabled']);
        
        $this->addSection('General Settings', 'Some settings may be hidden with the choices made for your school.');

        $setting = $sm->createOneByName('finance.invoice_text')
            ->setSettingType('html')
            ->setDisplayName('Invoice Text')
            ->setDescription('Text to appear in invoice, above invoice details and fees.');
        if (empty($setting->getValue())) 
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.invoice_notes')
            ->setSettingType('html')
            ->setDisplayName('Invoice Notes')
            ->setDescription('Text to appear in invoice, below invoice details and fees.');
        if (empty($setting->getValue())) 
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.invoicee_name_style')
            ->setSettingType('enum')
            ->setChoices([
                'finance.invoicee_name_style.surname_preferred_name' => 'surname_preferred_name',
                'finance.invoicee_name_style.official_name' => 'official_name'
            ])
            ->setDisplayName('Invoicee Name Style')
            ->setDescription('Determines how invoicee name appears on invoices and receipts.');
        if (empty($setting->getValue())) 
            $setting->setValue('surname_preferred_name');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.invoice_number')
            ->setSettingType('enum')
            ->setChoices([
                'finance.invoice_number.invoice_id' => 'invoice_id',
                'finance.invoice_number.person_id_invoice_id' => 'person_id_invoice_id',
                'finance.invoice_number.student_id_invoice_id' => 'student_id_invoice_id',
            ])
            ->setDisplayName('Invoice Number Style')
            ->setDescription('How should invoice numbers be constructed?');
        if (empty($setting->getValue()))
            $setting->setValue('invoice_id');
        $this->addSetting($setting, []);

        $this->addSection('Invoices');

        $setting = $sm->createOneByName('finance.receipt_text')
            ->setSettingType('html')
            ->setDisplayName('Receipt Text')
            ->setDescription('Text to appear in receipt, above receipt details and fees.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.receipt_notes')
            ->setSettingType('html')
            ->setDisplayName('Receipt Notes')
            ->setDescription('Text to appear in receipt, below receipt details and fees.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.hide_itemisation')
            ->setSettingType('boolean')
            ->setDisplayName('Hide Itemisation')
            ->setDescription('Hide fee and payment details in receipts?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, []);

        $this->addSection('Receipts');

        $setting = $sm->createOneByName('finance.reminder1_text')
            ->setSettingType('html')
            ->setDisplayName('Reminder 1 Text')
            ->setDescription('Text to appear in first level reminder level, above invoice details and fees.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.reminder2_text')
            ->setSettingType('html')
            ->setDisplayName('Reminder 2 Text')
            ->setValidators(null)
            ->setDescription('Text to appear in second level reminder level, above invoice details and fees.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.reminder3_text')
            ->setSettingType('html')
            ->setDisplayName('Reminder 3 Text')
            ->setDescription('Text to appear in third level reminder level, above invoice details and fees.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Reminders');

        $setting = $sm->createOneByName('finance.budget_categories')
            ->setSettingType('array')
            ->setDisplayName('Budget Categories')
            ->setDescription('List of budget categories.');
        if (empty($setting->getValue()))
            $setting->setValue(['Academic','Administration','Capital']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.expense_approval_type')
            ->setSettingType('enum')
            ->setChoices([
                'finance.expense_approval_type.one_of' => 'one_of',
                'finance.expense_approval_type.two_of' => 'two_of',
                'finance.expense_approval_type.chain_of_all' => 'chain_of_all'
            ])
            ->setDisplayName('Expense Approval Type')
            ->setDescription('How should expense approval be dealt with?');
        if (empty($setting->getValue()))
            $setting->setValue('one_of');
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.budget_level_expense_approval')
            ->setSettingType('boolean')
            ->setDisplayName('Budget Level Expense Approval')
            ->setDescription('Should approval from a budget member with Full access be required?');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.expense_request_template')
            ->setSettingType('html')
            ->setDisplayName('Expense Request Template')
            ->setDescription('An HTML template to be used in the description field of expense requests.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.allow_expense_add')
            ->setSettingType('boolean')
            ->setDisplayName('Allow Expense Add')
            ->setDescription('Allows privileged users to add expenses without going through request process.');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('finance.purchasing_officer')
            ->setSettingType('enum')
            ->setDisplayName('Purchasing Officer')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setDescription('Staff member responsible for purchasing for the school.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, ['placeholder' => 'Select Purchasing Officer...']);

        $setting = $sm->createOneByName('finance.reimbursement_officer')
            ->setSettingType('enum')
            ->setDisplayName('Reimbursement Officer')
            ->setChoices(StaffManager::getStaffListChoice())
            ->setDescription('Staff member responsible for reimbursing expenses.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting,  ['placeholder' => 'Select Reimbursement Officer...']);

        $this->addSection('Expenses');

        $this->setSectionsHeader('manage_finance_settings');

        $this->setSettingManager(null);
        
        return $this;
    }
}
