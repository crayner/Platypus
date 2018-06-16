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
org.name:
    type: array
    name: org.name
    displayName: 'Organisation Name'
    description: 'The name of your organisation'
    value:
        long: 'Busybee Learning'
        short: BEE
    choice: null
    validator: null
    role: ROLE_REGISTRAR
    defaultValue: {  }
    translateChoice: null
countrytype:
    type: text
    name: countrytype
    displayName: 'Country Type Form Handler'
    description: 'Determines how the country details are obtained and stored in the database.'
    value: Symfony\Component\Form\Extension\Core\Type\CountryType
    choice: null
    validator: null
    role: ROLE_SYSTEM_ADMIN
    defaultValue: null
    translateChoice: null
org.logo:
    type: image
    name: org.logo
    displayName: 'Organisation Logo'
    description: 'The organisation Logo'
    value: ''
    choice: null
    validator: App\Validator\Logo
    role: ROLE_REGISTRAR
    defaultValue: bundles/platypustemplateoriginal/img/bee.png
    translateChoice: null
org.transparent.logo:
    type: image
    name: org.transparent.logo
    displayName: 'Organisation Transparent Logo'
    description: 'The organisation Logo in a transparent form.  Recommended to be 80% opacity. Only PNG or GIF image formats support transparency.'
    value: ''
    choice: null
    validator: App\Validator\Logo
    role: ROLE_REGISTRAR
    defaultValue: bundles/platypustemplateoriginal/img/bee-transparent.png
    translateChoice: null
background.image:
    type: image
    name: background.image
    displayName: 'Background Image'
    description: 'Change the background displayed for the site.  The image needs to be a minimum of 1200px width.  You can load an image of 1M size, but the smaller the size the better.'
    value: ''
    choice: null
    validator: App\Core\Validator\BackgroundImage
    role: ROLE_ADMIN
    defaultValue: bundles/platypustemplateoriginal/img/backgroundPage.jpg
    translateChoice: null
date.format:
    type: array
    name: date.format
    displayName: 'Date Format'
    description: 'Display the date in reports in this format. Formats are found at http://php.net/manual/en/function.date.php'
    value:
        long: 'D, jS M/Y'
        short: j/m/Y
        widget: dMMMy
    choice: null
    validator: null
    role: ROLE_REGISTRAR
    defaultValue: {  }
    translateChoice: null
google:
    type: array
    name: google
    displayName: 'Google Authentication and App Access'
    description: 'Google Authentication and App Access details.'
    value:
        o_auth: '0'
        client_id: 142820932329-q1upj2ghkedceen3nhcp6l8uo6hulsl2.apps.googleusercontent.com
        client_secret: EZ9oJc3ughHh_2X27lkMexZ-
    choice: null
    validator: null
    role: ROLE_SYSTEM_ADMIN
    defaultValue: {  }
    translateChoice: null
LLL;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return get_class();
	}
}