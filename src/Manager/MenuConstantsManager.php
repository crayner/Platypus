<?php
namespace App\Manager;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

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
12:
    label: menu.admin.timetable
    name: Timetable Admin
    role: ROLE_ADMIN
    node: 1
    order: 12
    route: manage_timetables
13:
    label: menu.admin.people
    name: People Admin
    role: ROLE_STAFF
    node: 1
    order: 13
    route: manage_people
';
    CONST SECTIONS = '
System Admin:
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
        update:
            label: Update
            role: ROLE_SYSTEM_ADMIN
            route: installer_update
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
Timetable Admin:
    course_classes:
        manage_course_classes:
            label: "Manage Course & Classes"
            role: ROLE_ACTION
            route: manage_courses
            parameters: {}
            transDomain: System
    timetable:
        manage_timetable_columns:
            label: "Manage Columns"
            role: ROLE_ACTION
            route: manage_columns
            parameters: {}
            transDomain: Timetable         
        manage_timetables:
            label: "Manage Timetables"
            role: ROLE_ACTION
            route: manage_timetables
            parameters: {}
            transDomain: Timetable         
    hidden:
        course_edit:
            route: edit_course
            parameters:
                id: "%"
        timetable_edit:
            route: edit_timetable
            parameters:
                id: "%"
        column_edit:
            route: edit_column
            parameters:
                id: "%"
        edit_class:
            route: edit_class
            parameters:
                id: "%"
                course_id: "%"

';

    /**
     * getSections
     *
     * @return array
     */
    public function getSections(): array
    {
        $result = Yaml::parse(str_replace("\t", "    ", MenuConstantsManager::SECTIONS)) ?: [];
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/Menu');
        foreach ($finder as $file) {

            if (in_array($file->getExtension(), ['yml', 'yaml']))
            {
                $content = Yaml::parse(file_get_contents($file->getRealPath()));
                if ($content['section'])
                    $result = array_merge($result, $content['section']);
            }
        }
        return $result;
    }
}
