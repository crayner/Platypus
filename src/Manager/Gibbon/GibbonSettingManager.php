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
 * Date: 17/09/2018
 * Time: 07:01
 */
namespace App\Manager\Gibbon;

use App\Entity\Setting;
use App\Util\StringHelper;
use App\Validator\BackgroundImage;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GibbonSettingManager
 * @package App\Manager\Gibbon
 */
class GibbonSettingManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Setting::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonSetting';

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonSettingID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'call' => ['function' => 'createSettingName', 'options' => []],
            ],
            'combineField' => [
                'scope',
                'name',
            ],
        ],
        'description' => [
            'field' => 'description',
        ],
        'nameDisplay' => [
            'field' => 'display_name',
            'functions' => [
                'length' => 64,
            ],
        ],
        'scope' => [
            'field' => '',
        ],
        'value' => [
            'field' => 'value',
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonSchoolYear';

    /**
     * @var array
     */
    public $skipTruncate = [];

    /**
     * createSettingName
     *
     * @param $value
     * @param $options
     * @param ObjectManager $manager
     */
    public function createSettingName($value, $options, ObjectManager $manager)
    {
        return StringHelper::insertUnderScore(implode('.',$value));
    }

    /**
     * @var array
     */
    private $settings = [
        'system.absolute_url' => [
            'settingType' => 'url',
        ],
        'system.organisation_name' => [
            'settingType' => 'string',
            'name' => 'org.name.long',
        ],
        'system.organisation_name_short' => [
            'settingType' => 'string',
            'name' => 'org.name.short',
        ],
        'system.pagination' => [
            'settingType' => 'integer',
        ],
        'system.system_name' => [
            'settingType' => 'string',
        ],
        'system.index_text' => [
            'settingType' => 'html',
        ],
        'system.absolute_path' => [
            'settingType' => 'text',
        ],
        'system.timezone' => [
            'settingType' => 'string',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'timezone',
                ],
                'settingType' => 'string',
            ],
        ],
        'system.analytics' => [
            'settingType' => 'text',
        ],
        'system.email_link' => [
            'settingType' => 'text',
            'name' => null,
        ],
        'system.web_link' => [
            'settingType' => 'text',
        ],
        'system.default_assessment_scale' => [
            'settingType' => 'integer',
        ],
        'system.country' => [
            'settingType' => 'string',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'country',
                ],
                'settingType' => 'country',
            ],
        ],
        'system.organisation_logo' => [
            'settingType' => 'file',
            'name' => null,
        ],
        'system.calendar_feed' => [
            'settingType' => 'text',
        ],
        'activities.access' => [
            'settingType' => 'string',
            'validators' => [
                NotBlank::class => [],
            ],
            'functions' => [
                'safeString' => '',
            ],
        ],
        'activities.payment' => [
            'settingType' => 'string',
            'validators' => [
                NotBlank::class => [],
            ],
            'functions' => [
                'safeString' => '',
            ],
        ],
        'activities.enrolment_type' => [
            'settingType' => 'string',
            'validators' => [
                NotBlank::class => [],
            ],
            'functions' => [
                'safeString' => '',
            ],
        ],
        'activities.backup_choice' => [
            'settingType' => 'boolean',
        ],
        'activities.activity_types' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'application_form.introduction' => [
            'settingType' => 'html',
        ],
        'application_form.postscript' => [
            'settingType' => 'html',
        ],
        'application_form.scholarships' => [
            'settingType' => 'html',
        ],
        'application_form.agreement' => [
            'settingType' => 'html',
        ],
        'application_form.public_applications' => [
            'settingType' => 'boolean',
        ],
        'behaviour.positive_descriptors' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'behaviour.negative_descriptors' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'behaviour.levels' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'resources.categories' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'resources.purposes_general' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'system.version' => [
            'settingType' => 'string',
            'name' => null,
        ],
        'resources.purposes_restricted' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'system.organisation_email' => [
            'settingType' => 'text',
            'name' => 'org.email',
        ],
        'activities.date_type' => [
            'settingType' => 'choice',
            'choice' => ['term','flexible'],
            'functions' => [
                'safeString' => '',
            ],
        ],
        'system.install_type' => [
            'settingType' => 'string',
            'name' => null,
        ],
        'system.stats_collection' => [
            'settingType' => 'string',
            'name' => null,
        ],
        'activities.max_per_term' => [
            'settingType' => 'integer',
        ],
        'planner.lesson_details_template' => [
            'settingType' => 'html',
        ],
        'planner.teachers_notes_template' => [
            'settingType' => 'html',
        ],
        'planner.smart_block_template' => [
            'settingType' => 'html',
        ],
        'planner.unit_outline_template' => [
            'settingType' => 'html',
        ],
        'application_form.milestones' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'library.default_loan_length' => [
            'settingType' => 'integer',
        ],
        'behaviour.policy_link' => [
            'settingType' => 'url',
        ],
        'library.browse_bgcolor' => [
            'settingType' => 'colour',
            'name' => 'library.browse_bgcolour',
        ],
        'library.browse_bgimage' => [
            'settingType' => 'image',
            'validators' => [
                BackgroundImage::class => [],
            ],
        ],
        'system.password_policy_alpha' => [
            'settingType' => 'boolean',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'security.password.settings',
                    'mixed_case',
                ],
                'settingType' => 'boolean',
            ],
        ],
        'system.password_policy_numeric' => [
            'settingType' => 'boolean',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'security.password.settings',
                    'numbers',
                ],
                'settingType' => 'boolean',
            ],
        ],
        'system.password_policy_non_alpha_numeric' => [
            'settingType' => 'boolean',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'security.password.settings',
                    'specials',
                ],
                'settingType' => 'boolean',
            ],
        ],
        'system.password_policy_min_length' => [
            'settingType' => 'integer',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'security.password.settings',
                    'min_length',
                ],
                'settingType' => 'integer',
            ],
        ],
        'person_admin.ethnicity' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'person_admin.nationality' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'person_admin.residency_status' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'person_admin.personal_data_updater_required_fields' => [
            'settingType' => 'array',
            'functions' => [
                'unserialiser' => null,
            ],
        ],
        'school_admin.primary_external_assessment_by_year_group' => [
            'settingType' => 'array',
            'functions' => [
                'unserialiser' => null,
            ],
        ],
        'markbook.markbook_type' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'system.allowable_html' => [
            'settingType' => 'array',
            'name' => null,
        ],
        'application_form.how_did_you_hear' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'messenger.sms_username' => [
            'settingType' => 'string',
        ],
        'messenger.sms_password' => [
            'settingType' => 'string',
        ],
        'messenger.sms_url' => [
            'settingType' => 'url',
        ],
        'messenger.sms_urlcredit' => [
            'settingType' => 'url',
        ],
        'system.currency' => [
            'settingType' => 'string',
            'name' => 'currency',
        ],
        'system.enable_payments' => [
            'settingType' => 'boolean',
        ],
        'system.paypal_apiusername' => [
            'settingType' => 'string',
        ],
        'system.paypal_apipassword' => [
            'settingType' => 'string',
        ],
        'system.paypal_apisignature' => [
            'settingType' => 'text',
        ],
        'application_form.application_fee' => [
            'settingType' => 'string',
        ],
        'application_form.required_documents' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'application_form.required_documents_compulsory' => [
            'settingType' => 'boolean',
        ],
        'application_form.required_documents_text' => [
            'settingType' => 'html',
        ],
        'application_form.notification_student_default' => [
            'settingType' => 'boolean',
        ],
        'application_form.language_options_active' => [
            'settingType' => 'boolean',
        ],
        'application_form.language_options_blurb' => [
            'settingType' => 'html',
        ],
        'application_form.language_options_language_list' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'person_admin.personal_background' => [
            'settingType' => 'boolean',
        ],
        'person_admin.day_type_options' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'person_admin.day_type_text' => [
            'settingType' => 'html',
        ],
        'markbook.show_student_attainment_warning' => [
            'settingType' => 'boolean',
        ],
        'markbook.show_student_effort_warning' => [
            'settingType' => 'boolean',
        ],
        'markbook.show_parent_attainment_warning' => [
            'settingType' => 'boolean',
        ],
        'markbook.show_parent_effort_warning' => [
            'settingType' => 'boolean',
        ],
        'planner.allow_outcome_editing' => [
            'settingType' => 'boolean',
        ],
        'person_admin.privacy' => [
            'settingType' => 'boolean',
        ],
        'person_admin.privacy_blurb' => [
            'settingType' => 'html',
        ],
        'finance.invoice_text' => [
            'settingType' => 'html',
        ],
        'finance.invoice_notes' => [
            'settingType' => 'html',
        ],
        'finance.receipt_text' => [
            'settingType' => 'html',
        ],
        'finance.receipt_notes' => [
            'settingType' => 'html',
        ],
        'finance.reminder1_text' => [
            'settingType' => 'html',
        ],
        'finance.reminder2_text' => [
            'settingType' => 'html',
        ],
        'finance.reminder3_text' => [
            'settingType' => 'html',
        ],
        'finance.email' => [
            'settingType' => 'string',
            'validators' => [
                Email::class => [],
            ],
        ],
        'application_form.notification_parents_default' => [
            'settingType' => 'boolean',
        ],
        'person_admin.privacy_options' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'planner.sharing_default_parents' => [
            'settingType' => 'boolean',
        ],
        'planner.sharing_default_students' => [
            'settingType' => 'boolean',
        ],
        'students.extended_brief_profile' => [
            'settingType' => 'boolean',
        ],
        'application_form.notification_parents_message' => [
            'settingType' => 'html',
        ],
        'application_form.notification_student_message' => [
            'settingType' => 'html',
        ],
        'finance.invoice_number' => [
            'settingType' => 'string',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'person_admin.departure_reasons' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'system.google_oauth' => [
            'settingType' => 'boolean',
            'name' => 'google.o_auth',
        ],
        'system.google_client_name' => [
            'settingType' => 'text',
            'name' => 'google.client_name',
        ],
        'system.google_client_id' => [
            'settingType' => 'text',
            'name' => 'google.client_id',
        ],
        'system.google_client_secret' => [
            'settingType' => 'text',
            'name' => 'google.client_secret',
        ],
        'system.google_redirect_uri' => [
            'settingType' => 'url',
            'name' => 'google.redirect_uri',
        ],
        'system.google_developer_key' => [
            'settingType' => 'text',
            'name' => 'google.developer_key',
        ],
        'markbook.personalised_warnings' => [
            'settingType' => 'boolean',
        ],
        'activities.disable_external_provider_signup' => [
            'settingType' => 'boolean',
        ],
        'activities.hide_external_provider_cost' => [
            'settingType' => 'boolean',
        ],
        'system.cutting_edge_code' => [
            'settingType' => 'boolean',
            'name' => null,
        ],
        'system.cutting_edge_code_line' => [
            'settingType' => 'integer',
            'name' => null,
        ],
        'system.gibbonedu_com_organisation_name' => [
            'settingType' => 'text',
            'name' => null,
        ],
        'system.gibbonedu_com_organisation_key' => [
            'settingType' => 'text',
            'name' => null,
        ],
        'application_form.student_default_email' => [
            'settingType' => 'text',
            'validators' => [
                Email::class => [],
            ],
        ],
        'application_form.student_default_website' => [
            'settingType' => 'url',
        ],
        'school_admin.student_agreement_options' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'markbook.attainment_alternative_name' => [
            'settingType' => 'string',
        ],
        'markbook.effort_alternative_name' => [
            'settingType' => 'string',
        ],
        'markbook.attainment_alternative_name_abrev' => [
            'settingType' => 'string',
            'name' => 'markbook.attainment_alternative_name_abbrev',
        ],
        'markbook.effort_alternative_name_abrev' => [
            'settingType' => 'string',
            'name' => 'markbook.effort_alternative_name_abbrev',
        ],
        'planner.parent_weekly_email_summary_include_behaviour' => [
            'settingType' => 'boolean',
        ],
        'finance.finance_online_payment_enabled' => [
            'settingType' => 'boolean',
        ],
        'finance.finance_online_payment_threshold' => [
            'settingType' => 'string',
        ],
        'departments.make_departments_public' => [
            'settingType' => 'boolean',
        ],
        'system.session_duration' => [
            'settingType' => 'integer',
            'replaceParameter' => [
                'file' => 'platypus.yaml',
                'setting' => [
                    'idle_timeout',
                ],
                'settingType' => 'minutes',
            ],
        ],
        'planner.make_units_public' => [
            'settingType' => 'boolean',
        ],
        'messenger.message_bubble_width_type' => [
            'settingType' => 'string',
            'choice' => ['regular','wide'],
            'functions' => [
                'safeString' => null,
            ],
        ],
        'messenger.message_bubble_bgcolor' => [
            'settingType' => 'colour',
            'name' => 'messenger.message_bubble_bgcolour'
        ],
        'messenger.message_bubble_auto_hide' => [
            'settingType' => 'boolean',
        ],
        'students.enable_student_notes' => [
            'settingType' => 'boolean',
        ],
        'finance.budget_categories' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'finance.expense_approval_type' => [
            'settingType' => 'string',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'finance.budget_level_expense_approval' => [
            'settingType' => 'boolean',
        ],
        'finance.expense_request_template' => [
            'settingType' => 'html',
        ],
        'finance.purchasing_officer' => [
            'settingType' => 'integer',
        ],
        'finance.reimbursement_officer' => [
            'settingType' => 'integer',
        ],
        'messenger.enable_home_screen_widget' => [
            'settingType' => 'boolean',
        ],
        'person_admin.enable_public_registration' => [
            'settingType' => 'boolean',
        ],
        'person_admin.public_registration_minimum_age' => [
            'settingType' => 'integer',
            'validators' => [
                Range::class => ['max' => 25, 'min' => 0],
            ],
        ],
        'person_admin.public_registration_default_status' => [
            'settingType' => 'choice',
            'choice' => ['full', 'pending'],
            'functions' => [
                'safeString' => '',
                'inArray' => ['default' =>'pending', 'choice' => ['full', 'pending']],
            ],
        ],
        'person_admin.public_registration_default_role' => [
            'settingType' => 'integer',
        ],
        'person_admin.public_registration_intro' => [
            'settingType' => 'html',
        ],
        'person_admin.public_registration_privacy_statement' => [
            'settingType' => 'html',
        ],
        'person_admin.public_registration_agreement' => [
            'settingType' => 'html',
        ],
        'person_admin.public_registration_postscript' => [
            'settingType' => 'html',
        ],
        'system.alarm' => [
            'settingType' => 'string',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'behaviour.enable_descriptors' => [
            'settingType' => 'boolean',
        ],
        'behaviour.enable_levels' => [
            'settingType' => 'boolean',
        ],
        'formal_assessment.internal_assessment_types' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'system_admin.custom_alarm_sound' => [
            'settingType' => 'file',
            'validators' => [
                File::class => ['mimeTypes' => ['audio/*']],
            ],
        ],
        'school_admin.facility_types' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'finance.allow_expense_add' => [
            'settingType' => 'boolean',
        ],
        'system.organisation_administrator' => [
            'settingType' => 'integer',
        ],
        'system.organisation_dba' => [
            'settingType' => 'integer',
        ],
        'system.organisation_admissions' => [
            'settingType' => 'integer',
        ],
        'finance.hide_itemisation' => [
            'settingType' => 'boolean',
        ],
        'application_form.auto_house_assign' => [
            'settingType' => 'boolean',
        ],
        'tracking.external_assessment_data_points' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'tracking.internal_assessment_data_points' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'behaviour.enable_behaviour_letters' => [
            'settingType' => 'boolean',
        ],
        'behaviour.behaviour_letters_letter1_count' => [
            'settingType' => 'integer',
        ],
        'behaviour.behaviour_letters_letter1_text' => [
            'settingType' => 'html',
        ],
        'behaviour.behaviour_letters_letter2_count' => [
            'settingType' => 'integer',
        ],
        'behaviour.behaviour_letters_letter2_text' => [
            'settingType' => 'html',
        ],
        'behaviour.behaviour_letters_letter3_count' => [
            'settingType' => 'integer',
        ],
        'behaviour.behaviour_letters_letter3_text' => [
            'settingType' => 'html',
        ],
        'markbook.enable_column_weighting' => [
            'settingType' => 'boolean',
        ],
        'system.first_day_of_the_week' => [
            'settingType' => 'string',
            'functions' => [
                'length' => 3,
                'safeString' => '',
            ],
            'choice' => ['mon', 'sun'],
        ],
        'application_form.username_format' => [
            'settingType' => 'text',
        ],
        'staff.job_opening_description_template' => [
            'settingType' => 'html',
        ],
        'staff.staff_application_form_introduction' => [
            'settingType' => 'html',
            'name' => 'staff.application_form_introduction',
        ],
        'staff.staff_application_form_postscript' => [
            'settingType' => 'html',
            'name' => 'staff.application_form_postscript',
        ],
        'staff.staff_application_form_agreement' => [
            'settingType' => 'html',
            'name' => 'staff.application_form_agreement',
        ],
        'staff.staff_application_form_milestones' => [
            'settingType' => 'array',
            'name' => 'staff.application_form_milestones',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'staff.staff_application_form_required_documents' => [
            'settingType' => 'array',
            'name' => 'staff.application_form_required_documents',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'staff.staff_application_form_required_documents_compulsory' => [
            'name' => 'staff.application_form_required_documents_compulsory',
            'settingType' => 'boolean',
        ],
        'staff.staff_application_form_required_documents_text' => [
            'settingType' => 'html',
            'name' => 'staff.application_form_required_documents_text',
        ],
        'staff.staff_application_form_notification_default' => [
            'settingType' => 'boolean',
            'name' => 'staff.application_form_notification_default',
        ],
        'staff.staff_application_form_notification_message' => [
            'settingType' => 'html',
            'name' => 'staff.application_form_notification_message',
        ],
        'staff.staff_application_form_default_email' => [
            'settingType' => 'string',
            'name' => 'staff.application_form_default_email',
            'validators' => [
                Email::class => [],
            ],
        ],
        'staff.staff_application_form_default_website' => [
            'settingType' => 'url',
            'name' => 'staff.application_form_default_website',
        ],
        'staff.staff_application_form_username_format' => [
            'settingType' => 'text',
            'name' => 'staff.application_form_username_format',
        ],
        'system.organisation_hr' => [
            'settingType' => 'integer',
        ],
        'staff.staff_application_form_questions' => [
            'name' => 'staff.application_form_questions',
            'settingType' => 'html',
        ],
        'staff.salary_scale_positions' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'staff.responsibility_posts' => [
            'settingType' => 'multiChoice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'students.application_form_sentext' => [
            'settingType' => 'html',
        ],
        'students.application_form_referee_link' => [
            'settingType' => 'url',
        ],
        'person_admin.religions' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'staff.application_form_referee_link' => [
            'settingType' => 'url',
        ],
        'markbook.enable_raw_attainment' => [
            'settingType' => 'boolean',
        ],
        'markbook.enable_group_by_term' => [
            'settingType' => 'boolean',
        ],
        'markbook.enable_effort' => [
            'settingType' => 'boolean',
        ],
        'markbook.enable_rubrics' => [
            'settingType' => 'boolean',
        ],
        'school_admin.staff_dashboard_default_tab' => [
            'settingType' => 'string',
        ],
        'school_admin.student_dashboard_default_tab' => [
            'settingType' => 'string',
        ],
        'school_admin.parent_dashboard_default_tab' => [
            'settingType' => 'string',
        ],
        'system.enable_mailer_smtp' => [
            'settingType' => 'boolean',
            'name' => null,
        ],
        'system.mailer_smtphost' => [
            'settingType' => 'text',
            'name' => null,
        ],
        'system.mailer_smtpport' => [
            'settingType' => 'integer',
            'name' => null,
        ],
        'system.mailer_smtpusername' => [
            'settingType' => 'string',
            'name' => null,
        ],
        'system.mailer_smtppassword' => [
            'settingType' => 'string',
            'name' => null,
        ],
        'system.main_menu_category_order' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'attendance.attendance_reasons' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'attendance.attendance_medical_reasons' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'attendance.attendance_enable_medical_tracking' => [
            'settingType' => 'boolean',
        ],
        'students.medical_illness_symptoms' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'staff_application_form.staff_application_form_public_applications' => [
            'settingType' => 'boolean',
            'name' => 'staff.application_form_public_applications'
        ],
        'individual_needs.targets_template' => [
            'settingType' => 'html',
        ],
        'individual_needs.teaching_strategies_template' => [
            'settingType' => 'html',
        ],
        'individual_needs.notes_review_template' => [
            'settingType' => 'html',
        ],
        'attendance.attendance_clinotify_by_roll_group' => [
            'settingType' => 'boolean',
        ],
        'attendance.attendance_clinotify_by_class' => [
            'settingType' => 'boolean',
        ],
        'attendance.attendance_cliadditional_users' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'students.note_creation_notification' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'finance.invoicee_name_style' => [
            'settingType' => 'text',
        ],
        'planner.share_unit_outline' => [
            'settingType' => 'boolean',
        ],
        'attendance.student_self_registration_ipaddresses' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'application_form.internal_documents' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'attendance.prefill_roll_group' => [
            'settingType' => 'boolean',
        ],
        'attendance.prefill_class' => [
            'settingType' => 'boolean',
        ],
        'attendance.prefill_person' => [
            'settingType' => 'boolean',
        ],
        'attendance.default_roll_group_attendance_type' => [
            'settingType' => 'string',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'attendance.default_class_attendance_type' => [
            'settingType' => 'string',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'students.academic_alert_low_threshold' => [
            'settingType' => 'integer',
        ],
        'students.academic_alert_medium_threshold' => [
            'settingType' => 'integer',
        ],
        'students.academic_alert_high_threshold' => [
            'settingType' => 'integer',
        ],
        'students.behaviour_alert_low_threshold' => [
            'settingType' => 'integer',
        ],
        'students.behaviour_alert_medium_threshold' => [
            'settingType' => 'integer',
        ],
        'students.behaviour_alert_high_threshold' => [
            'settingType' => 'integer',
        ],
        'markbook.enable_display_cumulative_marks' => [
            'settingType' => 'boolean',
        ],
        'application_form.scholarship_options_active' => [
            'settingType' => 'boolean',
        ],
        'application_form.payment_options_active' => [
            'settingType' => 'boolean',
        ],
        'application_form.sen_options_active' => [
            'settingType' => 'boolean',
        ],
        'timetable_admin.auto_enrol_courses' => [
            'settingType' => 'boolean',
        ],
        'application_form.available_years_of_entry' => [
            'settingType' => 'choice',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'application_form.enable_limited_years_of_entry' => [
            'settingType' => 'boolean',
        ],
        'person_admin.unique_email_address' => [
            'settingType' => 'boolean',
        ],
        'planner.parent_weekly_email_summary_include_markbook' => [
            'settingType' => 'boolean',
        ],
        'system.name_format_staff_formal' => [
            'settingType' => 'text',
        ],
        'system.name_format_staff_formal_reversed' => [
            'settingType' => 'text',
        ],
        'system.name_format_staff_informal' => [
            'settingType' => 'text',
        ],
        'system.name_format_staff_informal_reversed' => [
            'settingType' => 'text',
        ],
        'attendance.self_registration_redirect' => [
            'settingType' => 'boolean',
        ],
        'data_updater.cutoff_date' => [
            'settingType' => 'date',
        ],
        'data_updater.redirect_by_role_category' => [
            'settingType' => 'text',
            'functions' => [
                'safeString' => '',
            ],
        ],
        'data_updater.required_updates' => [
            'settingType' => 'boolean',
        ],
        'data_updater.required_updates_by_type' => [
            'settingType' => 'array',
            'functions' => [
                'commaList' => '',
            ],
        ],
        'markbook.enable_modified_assessment' => [
            'settingType' => 'boolean',
        ],
    ];

    /**
     * postFieldData
     *
     * @param string $entityName
     * @param array $newData
     * @param string $field
     * @param $value
     * @return array
     */
    public function postFieldData(string $entityName, array $newData, string $field, $value): array
    {
        if ($field !== 'value')
            return $newData;

        $newData['name'] = str_replace('user_admin', 'person_admin', $newData['name']);

        if (empty($this->settings[$newData['name']]))
            trigger_error(sprintf('No definition for setting %s was found.', $newData['name']));

        $newData['setting_type'] = $this->settings[$newData['name']]['settingType'];

        if (isset($this->settings[$newData['name']]['validators']))
        {
            $vals = [];
            foreach($this->settings[$newData['name']]['validators'] as $q=>$w)
            {
                $vals[] = new $q($w);
            }
            $newData['validators'] = $vals;
        }

        if ($newData['setting_type'] === 'replace')
            $newData = $this->replaceParameter($newData, $this->settings[$newData['name']]['replaceParameter']);

        if (isset($this->settings[$newData['name']]['functions']))
            foreach($this->settings[$newData['name']]['functions'] as $func=>$options) {
                 if (! is_string($func))
                    dd([$newData['name'], $this->settings[$newData['name']]]);
                $newData = $this->$func($options, $newData);
            }

if (empty($newData['name']))
    dd([$newData, $this]);

        if (isset($this->settings[$newData['name']]['name']))
            $newData['name'] = $this->settings[$newData['name']]['name'];

        if (isset($this->settings[$newData['name']]['choice']))
            $newData['choice'] = $this->settings[$newData['name']]['choice'];

        switch ($newData['setting_type']){
            case 'colour':
            case 'string':
                $newData['value'] = mb_substr($value, 0, 25);
                break;
            case 'integer':
                $newData['value'] = intval($value);
                break;
            case 'boolean':
                $newData['value'] = in_array(strtoupper($value), ['Y','ON']) ? '1' : '0' ;
                break;
            case 'currency':
                $newData['value'] = mb_substr($newData['value'], 0, mb_strpos($newData['value'] , ' ')) ;
                $newData['settingType'] = 'string';
                $newData['validators'] = [
                    new Currency(),
                ];
                break;
        }

        return $newData;
    }

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @param array $records
     * @param ObjectManager $manager
     * @return array
     */
    public function postRecord(string $entityName, array $newData, array $records, ObjectManager $manager): array
    {
        if (is_array($newData['value']))
            $newData['value'] = serialize($newData['value']);

        if (isset($newData['validators']) && is_array($newData['validators']))
            $newData['validators'] = serialize($newData['validators']);

        if (isset($newData['choice']) && is_array($newData['choice']))
            $newData['choice'] = serialize($newData['choice']);

        if (! is_null($newData['name']))
            $records[] = $newData;
        return $records;
    }

    /**
     * length
     *
     * @param $options
     * @param array $newData
     * @return array
     */
    private function length(int $options, array $newData): array
    {
        $newData['value'] = mb_substr($newData['value'], 0, $options);
        return $newData;
    }

    /**
     * safeString
     *
     * @param $options
     * @param array $newData
     * @return array
     */
    private function safeString($options, array $newData): array
    {
        $newData['value'] = StringHelper::safeString($newData['value'], true);
        return $newData;
    }

    /**
     * safeString
     *
     * @param array $newData
     * @return array
     */
    private function commaList($options, array $newData): array
    {
        $newData['description'] = str_replace('Comma-seperated list', 'List', $newData['description']);
        $newData['description'] = str_replace('Comma-separated list', 'List', $newData['description']);
        $newData['value'] = explode(',', $newData['value']);
        foreach($newData['value'] as $q=>$w)
        {
            $newData['value'][$q] = StringHelper::insertUnderScore($w);
        }

        return $newData;
    }

    /**
     * inArray
     *
     * @param $value
     * @param $options
     * @return array
     */
    private function inArray($options, array $newData): array
    {
        if (empty($options['default']) || empty($options['choice']))
            return $newData['value'];
        $newData['value'] = in_array($newData['value'], $options['choice']) ? $newData['value'] : $options['default'] ;
        return $newData;
    }

    /**
     * replaceParameter
     *
     * @param $newData
     * @param $details
     */
    private function replaceParameter($newData, $details)
    {
        $newData['setting_type'] = 'string';
        $file = realpath(__DIR__. '/../../../config/packages/'.$details['file']);

        if (! file_exists($file))
            return $newData;

        $parameters = Yaml::parse(file_get_contents($file));

        switch ($details['settingType'])
        {
            case 'boolean':
                $newData['value'] = in_array(strtoupper($newData['value']), ['Y','ON']) ? true : false ;
                break;
            case 'country':
                $newData['value'] = array_search($newData['value'], Intl::getRegionBundle()->getCountryNames());
                break;
            case 'integer':
                $newData['value'] = intval($newData['value']);
                break;
            case 'minutes':
                $newData['value'] = intval($newData['value'] / 60);
                break;
        }

        $parameters['parameters'] = $this->setParameter($parameters['parameters'], $newData['value'], $details['setting']);

        file_put_contents($file, Yaml::dump($parameters, 8));

        $newData['name'] = null;

        return $newData;
    }

    private function setParameter($parameters, $value, $setting)
    {
        if (count($setting) > 1)
        {
            $w = array_shift($setting);
            $parameters[$w] = $this->setParameter($parameters[$w], $value, $setting);
        }
        $w = array_shift($setting);

        $parameters[$w] = $value;
        return $parameters;
    }

    /**
     * unserialiser
     *
     * @param array $newData
     * @return array
     */
    private function unserialiser($options, array $newData): array
    {
        $newData['value'] = unserialize($newData['value']);

        return $newData;
    }

    /**
     * @var array
     */
    private $keepMe = [];

    /**
     * preTruncate
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function preTruncate(string $entityName): void
    {
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('version');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('template.name');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('personal.title.list');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('date.format');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('background.image');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('org.logo');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('org.transparent.logo');
        $this->keepMe[] = $this->getObjectManager()->getRepository(Setting::class)->findOneByName('countrytype');
    }

    /**
     * postLoad
     *
     * @param string $entityName
     */
    public function postLoad(string $entityName)
    {
        if (!empty($this->keepMe)) {
            $conn = $this->getObjectManager()->getConnection();
            $tableName = $this->getObjectManager()->getClassMetadata(Setting::class)->table['name'];
            $this->beginTransaction();
            foreach ($this->keepMe as $setting) {
                if ($setting instanceof Setting) {
                    $setting->setId(null);
                    $item = [];
                    $item['setting_type'] = $setting->getSettingType();
                    $item['name'] = $setting->getName();
                    $item['display_name'] = $setting->getDisplayName();
                    $item['description'] = $setting->getDescription();
                    $item['value'] = $setting->getValue();
                    $item['choice'] = serialize($setting->getChoice());
                    $item['validators'] = serialize($setting->getValidators());
                    $item['role'] = $setting->getRole();
                    $item['default_value'] = $setting->getDefaultValue();
                    $item['translate_choice'] = $setting->getTranslateChoice();

                    $conn->insert($tableName, $item);
                }
            }
            $this->commit();
        }
    }
}