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
	label: Admin
	role: ROLE_USER
	order: 1
	menu: 1
';
    CONST ITEMS = '
10:
    label: School Admin
    name: School Admin
    role: ROLE_REGISTRAR
    node: 1
    order: 10
    route: school_year_manage
11:
    label: System Admin
    name: System Admin
    role: ROLE_REGISTRAR
    node: 1
    order: 11
    route: manage_system_settings
12:
    label: Timetable Admin
    name: Timetable Admin
    role: ROLE_ADMIN
    node: 1
    order: 12
    route: manage_timetables
13:
    label: People Admin
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
            label: Manage Themes
            role: ROLE_SYSTEM_ADMIN
            route: manage_themes
            parameters: {}
            transDomain: System
        system_check:
            label: System Check
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
            label: Manage Language Settings
            role: ROLE_REGISTRAR
            route: manage_system_settings
            parameters: 
                _fragment: section_Localisation_collection_4
            transDomain: System
        manage_notification_events:
            label: Notification Event Settings
            role: ROLE_REGISTRAR
            route: manage_notification_events
            parameters: {}
            transDomain: System
        string_replacement:
            label: String Replacement
            role: ROLE_REGISTRAR
            route: manage_string_replacement
            parameters: {}
            transDomain: System
        manage_system_settings:
            label: Manage System Settings
            role: ROLE_REGISTRAR
            route: manage_system_settings
            parameters: {}
            transDomain: System
        third_party_settings:
            label: Third Party Settings
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
