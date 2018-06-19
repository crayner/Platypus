<?php
namespace App\Manager;

class MenuManagerConstants
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
';
    CONST SECTIONS = '
School Admin:
    groupings:
        houses_edit:
            label: menu.houses.manage
            role: ROLE_REGISTRAR
            route: houses_edit
            parameters: {}
            transDomain: School
        manage_year_groups:
            label: menu.year_groups.manage
            role: ROLE_REGISTRAR
            route: year_groups_manage
            parameters: {}
            transDomain: School
    people:
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
            route: manage_activities_settings
            parameters: {}
            transDomain: System
        library_settings:
            label: manage_library_settings
            role: ROLE_PRINCIPAL
            route: manage_library_settings
            parameters: {}
            transDomain: System
        planner_settings:
            label: manage_planner_settings
            role: ROLE_PRINCIPAL
            route: manage_planner_settings
            parameters: {}
            transDomain: System
        resource_settings:
            label: manage_resource_settings
            role: ROLE_PRINCIPAL
            route: resource_settings_manage
            parameters: {}
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
                _fragment: specialdays
            transDomain: SchoolYear 
        manage_year_terms:
            label: menu.school_year.terms
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                _fragment: terms
            transDomain: SchoolYear 
    hidden:
        - school_year_edit
        - multiple_settings_manage
';
    /*
	CONST NODES = '
1:
	name: System
	label: menu.admin.node
	role: ROLE_USER
	order: 1
	menu: 1
2:
    name: People
    label: menu.people.title
    role: ROLE_USER
    order: 2
    menu: 2
4:
    name: Learn
    label: menu.learn.title
    role: ROLE_USER
    order: 4
    menu: 4
';

	CONST ITEMS = '
10:
    label: menu.admin.school
    name: School Admin
    role: ROLE_REGISTRAR
    node: 1
    order: 10
    route: calendar_years
11:
    label: menu.setting.manage
    name: Setting Management
    role: ROLE_REGISTRAR
    node: 1
    order: 11
    route: setting_manage
12:
    label: menu.timetable.manage
    name: Timetable Management
    role: ROLE_REGISTRAR
    node: 1
    order: 11
    route: timetable_manage
    transDomain: School
20:
    label: menu.people.manage
    name: People Admin
    role: ROLE_ADMIN
    node: 2
    order: 20
    route: person_manage
40:
    label: menu.external_activity.manage
    name: External Activity Manage
    role: ROLE_PRINCIPAL
    node: 4
    order: 40
    route: external_activity_list
41:
    label: menu.department.manage
    name: Department Manage
    role: ROLE_REGISTRAR
    node: 4
    order: 41
    route: department_edit
    parameters:
        id: Add
';

	CONST SECTIONS = '
System Admin:
    extend_update:
        acknowledgement:
            route: acknowledgement
            label: menu.site.acknowledgement
            role: []
            parameters: {}
    settings:
        string_replacement:
            label: menu.string_replacement.manage
            role: ROLE_SYSTEM_ADMIN
            route: string_replacement
            parameters: {}
        setting_manage:
            label: menu.setting.manage
            role: ROLE_SYSTEM_ADMIN
            route: setting_manage
            parameters: {}
        third_party:
            label: menu.third.party
            role: ROLE_SYSTEM_ADMIN
            route: third_party_settings
            parameters: {}
    hidden:
        - setting_edit
        - setting_edit_name
        - page_edit
School Admin:
    groupings:
        department_edit:
            label: menu.school.department.edit
            role: ROLE_REGISTRAR
            route: department_edit
            parameters:
                id: Add
        houses_edit:
            label: menu.setting.houses
            role: ROLE_REGISTRAR
            route: houses_edit
            parameters: {}
        manage_calendar_grades:
            label: menu.school_year.grades
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                _fragment: calendarGrades
        manage_rolls:
            label: roll.menu.list
            transDomain: School
            route: roll_list
            parameters: {}
            role: ROLE_REGISTRAR
    others:
        campus_manage:
	        label: menu.campus.manage.title
	        name: Campus Management
	        role: ROLE_REGISTRAR
	        route: campus_manage
	        parameters: {}
	        transDomain: Facility
        space_list:
	        label: menu.space.list.title
	        name: Space Management
	        role: ROLE_REGISTRAR
	        route: space_list
	        parameters: {}
	        transDomain: Facility
        space_type:
	        label: menu.space.type.title
	        name: Space Management
	        role: ROLE_SYSTEM_ADMIN
	        route: facility_type_manage
	        transDomain: Setting
	        parameters: {}
	        target:
                name: Setting_Facility_Type
                options: width=1200,height=900
    years_days_times:
        calendar_years:
            label: menu.school_year.manage
            role: ROLE_REGISTRAR
            route: calendar_years
            parameters: {}
        school_days_times:
            route: school_days_times
            role: ROLE_REGISTRAR
            label: menu.school.daysandtimes
            parameters: {}
        display_calendar:
            route: calendar_display
            role: ROLE_REGISTRAR
            label: menu.school_year.display
            parameters: 
                id: current
                closeWindow: closeWindow 
            target:
                name: Calendar
                options: width=1200,height=900
        manage_year_special_days:
            label: menu.school_year.special_days
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                _fragment: specialDays
        manage_year_terms:
            label: menu.school_year.terms
            role: ROLE_REGISTRAR
            route: school_year_edit
            parameters:
                id: current
                _fragment: terms
    hidden:
        - school_year_edit
        - edit_grade
        - student_add_to_calendar_group
        - space_edit
        - roll_edit
Person Admin:
    people_manage:
#        family_manage:
#            route: family_manage
#            label: menu.people.family.manage
#            role: ROLE_ADMIN
#            parameters: { }
        person_manage:
            route: person_manage
            label: menu.people.manage
            role: ROLE_ADMIN
            parameters: { }
    hidden:
        - person_edit
        - user_manage
        - student_manage
        - staff_manage
        - family_edit
Timetable Admin:
    course_class_manage:
        course_list:
            route: course_list
            label: course.list.menu.label
            transDomain: School
            role: ROLE_REGISTRAR
            parameters: {}
    timetable_manage:
        timetable_manage:
            route: timetable_manage
            label: timetable.manage.menu.label
            transDomain: Timetable
            role: ROLE_PRINCIPAL
            parameters: {}
    line_manage:
        line_manage:
            route: line_list
            label: line.list.menu.label
            transDomain: Timetable
            role: ROLE_PRINCIPAL
            parameters: {}
    hidden:
        - course_edit
        - face_to_face_edit
        - timetable_edit
        - timetable_days_edit
        - line_manage
        - timetable_assign_days
Activities:
    activities:
        external_activities:
            route: external_activity_list
            label: menu.activities.external.list
            role: ROLE_ADMIN
            parameters: {}
            transDomain: School
    departments:
        department_edit:
            label: menu.school.department.edit
            role: ROLE_REGISTRAR
            route: department_edit
            parameters:
                id: Add
    hidden:
        - external_activity_edit
';
    */
}
