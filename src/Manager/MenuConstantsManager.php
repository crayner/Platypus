<?php
namespace App\Manager;

class MenuConstantsManager
{
    CONST NODES = '
1:
	name: Admin
	label: menu.admin.node
	role: ROLE_USER
	order: 1
	menu: 1
';
    CONST ITEMS = '
10:
    label: menu.admin.school
    name: School Admin
    role: ROLE_REGISTRAR
    node: 1
    order: 10
    route: school_year_manage
11:
    label: menu.admin.system
    name: System Admin
    role: ROLE_REGISTRAR
    node: 1
    order: 11
    route: manage_system_settings
13:
    label: menu.admin.people
    name: People Admin
    role: ROLE_STAFF
    node: 1
    order: 13
    route: manage_people
';
    CONST SECTIONS = '
School Admin:
    assessment:
        manage_formal_assessments:
            label: manage_formal_assessments
            role: ROLE_PRINCIPAL
            route: manage_formal_assessments
            parameters: {}
            transDomain: System
        manage_external_assessments:
            label: manage_external_assessments
            role: ROLE_PRINCIPAL
            route: manage_external_assessments
            parameters: {}
            transDomain: School
        manage_scales:
            label: manage_scales
            role: ROLE_PRINCIPAL
            route: manage_scales
            parameters: {}
            transDomain: School
        markbook_settings:
            label: manage_markbook_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Markbook
            transDomain: System
        tracking_settings:
            label: manage_tracking_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Tracking
            transDomain: System
    groupings:
        manage_departments:
            label: manage_departments
            role: ROLE_REGISTRAR
            route: manage_departments
            parameters: {}
            transDomain: School
        houses_edit:
            label: menu.houses.manage
            role: ROLE_REGISTRAR
            route: houses_edit
            parameters: {}
            transDomain: School
        manage_roll_groups:
            label: manage_roll_groups
            role: ROLE_PRINCIPAL
            route: manage_roll_groups
            parameters: {}
            transDomain: School
        manage_year_groups:
            label: menu.year_groups.manage
            role: ROLE_REGISTRAR
            route: year_groups_manage
            parameters: {}
            transDomain: School
    other:
        dashboard_settings:
            label: manage_dashboard_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Dashboard
            transDomain: System
        manage_facilities:
            label: manage_facilities
            role: ROLE_PRINCIPAL
            route: manage_facilities
            parameters: {}
            transDomain: System
        facility_settings:
            label: manage_facility_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Facility
            transDomain: System
        file_extension:
            label: manage_file_extension
            role: ROLE_PRINCIPAL
            route: manage_file_extensions
            parameters: {}
            transDomain: System
        finance_settings:
            label: manage_finance_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Finance
            transDomain: System
        messenger_settings:
            label: manage_messenger_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Messenger
            transDomain: System
    people:
        alert_levels:
            label: manage_alert_levels
            role: ROLE_PRINCIPAL
            route: manage_alert_levels
            parameters: {}
            transDomain: System
        attendance_settings:
            label: manage_attendance_settings
            role: ROLE_PRINCIPAL
            route: manage_attendance_settings
            parameters: {}
            transDomain: System
        behaviour_settings:
            label: manage_behaviour_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Behaviour
            transDomain: System
        individual_needs:
            label: manage_individual_need_settings
            role: ROLE_PRINCIPAL
            route: manage_individual_need_settings
            parameters: {}
            transDomain: System
        student_settings:
            label: manage_student_settings
            role: ROLE_PRINCIPAL
            route: manage_student_settings
            parameters: {}
            transDomain: System
    teaching_learning:
        activities_settings:
            label: manage_activities_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Activities
            transDomain: System
        library_settings:
            label: manage_library_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Library
            transDomain: System
        planner_settings:
            label: manage_planner_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Planner
            transDomain: System
        resource_settings:
            label: manage_resource_settings
            role: ROLE_PRINCIPAL
            route: manage_settings
            parameters: 
                name: Resources
            transDomain: System
    years_days_times:
        school_days_times:
            label: menu.school.days_of_week
            transDomain: School
            route: days_of_week_manage
            parameters: {}
            role: ROLE_REGISTRAR
        school_year_manage:
            label: menu.school_year.manage
            role: ROLE_REGISTRAR
            route: school_year_manage
            parameters: {}
            transDomain: SchoolYear 
        manage_year_special_days:
            label: menu.school_year.special_days
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                tabName: specialdays
            transDomain: SchoolYear 
        manage_year_terms:
            label: menu.school_year.terms
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                tabName: terms
            transDomain: SchoolYear 
    hidden:
        school_year_edit:
            route: school_year_edit
            parameters:
                id: "%"
                tabName: "%"
        roll_group_edit:
            route: roll_group_edit
            parameters:
                id: "%"
                closeWindow: "%"
        department_edit:
            route: department_edit
            parameters:
                id: "%"
                tabName: "%"
        scale_edit:
            route: scale_edit
            parameters:
                id: "%"
        external_assessment_edit:
            route: external_assessment_edit
            parameters:
                id: "%"
System Admin:
    alarm:
        manage_alarm:
            label: manage_alarm_label
            role: ROLE_SYSTEM_ADMIN
            route: manage_alarm
            parameters: {}
            transDomain: System
    extend_and_update:
        manage_themes:
            label: manage_themes_label
            role: ROLE_SYSTEM_ADMIN
            route: manage_themes
            parameters: {}
            transDomain: System
        system_check:
            label: system_check_label
            role: ROLE_SYSTEM_ADMIN
            route: system_check
            parameters: {}
            transDomain: System
    settings:
        manage_language_setting:
            label: manage_lanuage_setting
            role: ROLE_REGISTRAR
            route: manage_system_settings
            parameters: 
                _fragment: section_Localisation_collection_4
            transDomain: System
        manage_notification_events:
            label: notification_event_settings
            role: ROLE_REGISTRAR
            route: manage_notification_events
            parameters: {}
            transDomain: System
        string_replacement:
            label: string_replacement
            role: ROLE_REGISTRAR
            route: manage_string_replacement
            parameters: {}
            transDomain: System
        manage_system_settings:
            label: manage_system_settings
            role: ROLE_REGISTRAR
            route: manage_system_settings
            parameters: {}
            transDomain: System
        third_party_settings:
            label: third_party_settings
            role: ROLE_REGISTRAR
            route: third_party_settings
            parameters: {}
            transDomain: System
    hidden:
        edit_string_replacement:
            route: edit_string_replacement
            parameters:
                id: "%"
        edit_notification_event:
            route: edit_notification_event
            parameters:
                id: "%"
People Admin:
    staff_management:
        staff_settings:
            label: "Manage Staff Settings"
            role: ROLE_ACTION
            route: manage_settings
            parameters: 
                name: Staff
            transDomain: System
        staff_application_form_settings:
            label: "Staff Application Form Settings"
            role: ROLE_ACTION
            route: manage_settings
            parameters: 
                name: StaffApplicationForm
            transDomain: System
    student_management:
        application_form_settings:
            label: "Application Form Settings"
            role: ROLE_ACTION
            route: manage_settings
            parameters: 
                name: ApplicationForm
            transDomain: System
        public_registration_settings:
            label: public_registration_settings
            role: ROLE_ACTION
            route: manage_settings
            parameters: 
                name: PublicRegistration
            transDomain: System
    people_management:
        manage_families:
            label: manage_families
            role: ROLE_ADMIN
            route: manage_families
            parameters: {}
            transDomain: Person
        manage_permissions:
            label: manage_permissions
            role: ROLE_ADMIN
            route: manage_permissions
            parameters: {}
            transDomain: Security
        manage_roles:
            label: manage_roles
            role: ROLE_ADMIN
            route: manage_roles
            parameters: 
                id: "%"
            transDomain: Security
        manage_custom_fields:
            label: manage_custom_fields
            role: ROLE_ADMIN
            route: manage_custom_fields
            parameters: 
                id: "%"
            transDomain: Person
        personnel_settings:
            label: personnel_settings
            role: ROLE_SYSTEM_ADMIN
            route: manage_settings
            parameters: 
                name: Personnel
            transDomain: Person
        manage_people:
            label: manage_people_label
            role: ROLE_MENU
            route: manage_people
            parameters: {}
            transDomain: Person
    hidden:
        person_edit:
            route: person_edit
            parameters:
                id: "%"
                tabName: "%"
        family_edit:
            route: family_edit
            parameters:
                id: "%"
                tabName: "%"
';
}
