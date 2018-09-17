<?php
namespace App\Organism;

use App\Manager\SettingInterface;

class Settings_0_1_00 implements SettingInterface
{
	const VERSION = '0.1.00';

	/**
	 * @return string
	 */
	public function getSettings()
	{
		return <<<LLL
org.name.long:
    settingType: string
    name: org.name
    displayName: 'Organisation Name'
    description: 'The name of your organisation'
    value: 'Busybee Learning'
    choice: null
    validators: null
    role: ROLE_REGISTRAR
    defaultValue: null
    translateChoice: System
org.name.short:
    settingType: string
    name: org.name
    displayName: 'Organisation Short Name'
    description: 'The short name of your organisation'
    value: 'BEE'
    choice: null
    validators: null
    role: ROLE_REGISTRAR
    defaultValue: null
    translateChoice: System
countrytype:
    settingType: text
    name: countrytype
    displayName: 'Country Type Form Handler'
    description: 'Determines how the country details are obtained and stored in the database.'
    value: Symfony\Component\Form\Extension\Core\Type\CountryType
    choice: null
    validators: null
    role: ROLE_SYSTEM_ADMIN
    defaultValue: null
    translateChoice: System
currency:
    settingType: text
    name: currency
    displayName: 'Currency'
    description: 'The currency used by the system for financial details.'
    value: Symfony\Component\Form\Extension\Core\Type\CurrencyType
    choice: null
    validators: { Symfony\Component\Validator\Constraints\Currency: {}}
    role: ROLE_SYSTEM_ADMIN
    defaultValue: AUD
    translateChoice: System
org.logo:
    settingType: image
    name: org.logo
    displayName: 'Organisation Logo'
    description: 'The organisation Logo'
    value: ''
    choice: null
    validators: { 'App\Validator\Logo':  {}}
    role: ROLE_REGISTRAR
    defaultValue: 'build/static/images/bee.png'
    translateChoice: System
org.transparent.logo:
    settingType: image
    name: org.transparent.logo
    displayName: 'Organisation Transparent Logo'
    description: 'The organisation Logo in a transparent form.  Recommended to be 80% opacity. Only PNG or GIF image formats support transparency.'
    value: ''
    choice: null
    validators: { 'App\Validator\Logo':  {}}
    role: ROLE_REGISTRAR
    defaultValue: 'build/static/images/bee-transparent.png'
    translateChoice: null
background.image:
    settingType: image
    name: background.image
    displayName: 'Background Image'
    description: 'Change the background displayed for the site.  The image needs to be a minimum of 1200px width.  You can load an image of 1M size, but the smaller the size the better.'
    value: ''
    choice: null
    validators: {'App\Validator\BackgroundImage': {}}
    role: ROLE_ADMIN
    defaultValue: overwrite/platypustemplateoriginal/img/backgroundPage.jpg
    translateChoice: null
date.format:
    settingType: array
    name: date.format
    displayName: 'Date Format'
    description: 'Display the date in reports in this format. Formats are found at http://php.net/manual/en/function.date.php'
    value:
        long: 'D, jS M/Y'
        short: j/m/Y
        widget: dMMMy
    choice: null
    validators: null
    role: ROLE_REGISTRAR
    defaultValue: {  }
    translateChoice: null
personal.title.list:
    settingType: array
    name: personal.title.list
    displayName: 'List of Personal Titles'
    description: 'List of personal titles used in the system.'
    value:
        - mr
        - mrs
        - ms
        - master
        - miss
        - dr
        - rev
    choice: null
    validators: null
    role: ROLE_REGISTRAR
    defaultValue: null
    translateChoice: null
template.name:
    settingType: string
    name: template.name
    displayName: 'Template Name'
    description: 'The name of the template being used.'
    value: null
    choice: null
    validators: null
    role: ROLE_SYSTEM_ADMIN
    defaultValue: PlatypusTemplateOriginal
    translateChoice: null
system_admin.custom_alarm_sound:
    settingType: file
    name: system_admin.custom_alarm_sound
    displayName: 'Custom Alarm File'
    description: 'Track the file for a customer alarm.'
    value: null
    choice: null
    validators: null
    role: ROLE_STAFF
    defaultValue: null
    translateChoice: null
LLL;

		/*
google:
    settingType: array
    name: google
    displayName: 'Google Authentication and App Access'
    description: 'Google Authentication and App Access details.'
    value:
        o_auth: '1'
        client_id: 142820932329-q1upj2ghkedceen3nhcp6l8uo6hulsl2.apps.googleusercontent.com
        client_secret: EZ9oJc3ughHh_2X27lkMexZ-
    choice: null
    validators: null
    role: ROLE_SYSTEM_ADMIN
    defaultValue: {  }
    translateChoice: null
		 */
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return get_class();
	}
}