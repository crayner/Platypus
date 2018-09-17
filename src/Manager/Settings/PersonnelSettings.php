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
 * Date: 22/06/2018
 * Time: 13:05
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PersonnelSettings
 * @package App\Manager\Settings
 */
class PersonnelSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Personnel';
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
        $sections = [];

        $setting = $sm->createOneByName('person_admin.ethnicity');

        $setting->setName('person_admin.ethnicity')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'List of Ethnicities')
            ->__set('description', 'List of Ethnicities.  Uses the Australian Standard to create this list');
        if (empty($setting->getValue())) {
            $setting->setValue([
                'Inadequately described' => '0000',
                'Not stated' => '0001',
                'Eurasian' => '0901',
                'Asian' => '0902',
                'African, so described' => '0903',
                'European, so described' => '0904',
                'Caucasian, so described' => '0905',
                'Creole, so described' => '0906',
                'Oceanian' => '1000',
                'Australian Peoples' => '1100',
                'New Zealand Peoples' => '1200',
                'Melanesian and Papuan' => '1300',
                'Micronesian' => '1400',
                'Polynesian' => '1500',
                'North-West European' => '2000',
                'British' => '2100',
                'Western European' => '2300',
                'Northern European' => '2400',
                'Southern and  Eastern European' => '3000',
                'Southern European' => '3100',
                'South Eastern European' => '3200',
                'Eastern European' => '3300',
                'North African and Middle Eastern' => '4000',
                'Arab' => '4100',
                'Peoples of the Sudan' => '4300',
                'Other North African and Middle Eastern' => '4900',
                'South-East  Asian' => '5000',
                'Mainland South-East Asian' => '5100',
                'Maritime South-East Asian' => '5200',
                'North-East Asian' => '6000',
                'Chinese Asian' => '6100',
                'Other North-East Asian' => '6900',
                'Southern and Central Asian' => '7000',
                'Southern Asian' => '7100',
                'Central Asian' => '7200',
                'Peoples of the Americas' => '8000',
                'North American' => '8100',
                'South American' => '8200',
                'Central American' => '8300',
                'Caribbean Islander' => '8400',
                'Sub-Saharan African' => '9000',
                'Central and West African' => '9100',
                'Southern and East African' => '9200',])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue([])
                ->__set('translateChoice', 'Person')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('person_admin.religion');

        $setting->setName('person_admin.religion')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'List of Religions')
            ->__set('description', 'List of Religions.  Uses the Australian Standard to create this list');
        if (empty($setting->getValue())) {
            $setting->setValue([
                'Aboriginal Evangelical Missions' => '2801',
                'Acts 2 Alliance' => '241d',
                'Agnosticism' => '720d',
                'Albanian Orthodox' => '223d',
                'Ancestor Veneration' => '605d',
                'Ancient Church of the East' => '222d',
                'Anglican Catholic Church' => '201d',
                'Anglican Church of Australia' => '201d',
                'Animism' => '613d',
                'Antiochian Orthodox' => '223d',
                'Apostolic Church (Australia)' => '240d',
                'Apostolic Church of Queensland' => '290d',
                'Armenian Apostolic' => '221d',
                'Assyrian Apostolic, nec' => '222d',
                'Assyrian Church of the East' => '222d',
                'Atheism' => '720d',
                'Australian Aboriginal Traditional Religions' => '601d',
                'Australian Christian Churches (Assemblies of God)' => '240d',
                'Baha\'i' => '603d',
                'Baptist' => '203d',
                'Bethesda Ministries International (Bethesda Churches)' => '240d',
                'Born Again Christian' => '280d',
                'Brethren' => '205d',
                'Buddhism' => '101d',
                'C3 Global (Christian City Church)' => '240d',
                'Caodaism' => '699d',
                'Catholic, nec' => '207d',
                'Chaldean Catholic' => '207d',
                'Chinese Religions, nec' => '605d',
                'Christadelphians' => '290d',
                'Christian and Missionary Alliance' => '280d',
                'Christian Church in Australia' => '241d',
                'Christian Community Churches of Australia' => '281d',
                'Christian Science' => '290d',
                'Church of Christ (Nondenominational)' => '211d',
                'Church of Jesus Christ of Latter-day Saints' => '215d',
                'Church of Scientology' => '699d',
                'Church of the Nazarene' => '280d',
                'Churches of Christ (Conference)' => '211d',
                'Community of Christ' => '215d',
                'Confucianism' => '605d',
                'Congregational' => '280d',
                'Coptic Orthodox' => '221d',
                'CRC International (Christian Revival Crusade)' => '240d',
                'Druidism' => '613d',
                'Druse' => '607d',
                'Eastern Orthodox, nec' => '223d',
                'Eckankar' => '699d',
                'Ethiopian Orthodox' => '221d',
                'Ethnic Evangelical Churches' => '280d',
                'Foursquare Gospel Church' => '241d',
                'Free Reformed' => '225d',
                'Full Gospel Church of Australia (Full Gospel Church)' => '241d',
                'Gnostic Christians' => '290d',
                'Grace Communion International (Worldwide Church of God)' => '291d',
                'Greek Orthodox' => '223d',
                'Hinduism' => '301d',
                'Humanism'=> '720d',
                'Independent Evangelical Churches' => '280d',
                'International Church of Christ' => '211d',
                'International Network of Churches (Christian Outreach Centres)' => '240d',
                'Islam' => '401d',
                'Jainism' => '699d',
                'Japanese Religions, nec' => '611d',
                'Jehovah\'s Witnesses' => '213d',
                'Judaism' => '501d',
                'Liberal Catholic Church' => '290d',
                'Lutheran' => '217d',
                'Macedonian Orthodox' => '223d',
                'Mandaean' => '690d',
                'Maronite Catholic' => '207d',
                'Melkite Catholic' => '207d',
                'Methodist, so described' => '281d',
                'Multi Faith' => '730d',
                'Nature Religions, nec' => '613d',
                'New Age' => '730d',
                'New Apostolic Church' => '290d',
                'New Churches (Swedenborgian)' => '290d',
                'No Religion, so described' => '710d',
                'Oriental Orthodox, nec' => '221d',
                'Other Anglican' => '201d',
                'Other Christian, nec' => '299d',
                'Other Protestant, nec' => '289d',
                'Other Spiritual Beliefs, nec' => '739d',
                'Own Spiritual Beliefs' => '730d',
                'Paganism' => '613d',
                'Pentecostal City Life Church' => '241d',
                'Pentecostal, nec' => '249d',
                'Presbyterian' => '225d',
                'Rastafari' => '699d',
                'Ratana (Maori)' => '290d',
                'Rationalism' => '720d',
                'Reformed' => '225d',
                'Religious Groups, nec' => '699d',
                'Religious Science' => '291d',
                'Religious Society of Friends (Quakers)' => '291d',
                'Revival Centres' => '241d',
                'Revival Fellowship' => '242d',
                'Rhema Family Church' => '241d',
                'Romanian Orthodox' => '223d',
                'Russian Orthodox' => '223d',
                'Salvation Army' => '227d',
                'Satanism' => '699d',
                'Secular Beliefs nec' => '729d',
                'Serbian Orthodox' => '223d',
                'Seventh-day Adventist' => '231d',
                'Shinto' => '611d',
                'Sikhism' => '615d',
                'Spiritualism' => '617d',
                'Sukyo Mahikari' => '611d',
                'Syrian Orthodox' => '221d',
                'Syro Malabar Catholic' => '207d',
                'Taoism' => '605d',
                'Temple Society' => '291d',
                'Tenrikyo' => '611d',
                'Theism' => '730d',
                'Theosophy' => '699d',
                'Ukrainian Catholic' => '207d',
                'Ukrainian Orthodox' => '223d',
                'United Methodist Church' => '281d',
                'United Pentecostal' => '241d',
                'Uniting Church' => '233d',
                'Universal Unitarianism' => '730d',
                'Victory Life Centre' => '242d',
                'Victory Worship Centre' => '242d',
                'Wesleyan Methodist Church' => '280d',
                'Western Catholic' => '207d',
                'Wiccan (Witchcraft)' => '613d',
                'Worship Centre network' => '242d',
                'Yezidi' => '690d',
                'Zoroastrianism' => '699d',
            ])->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                    ]
                )
                ->setDefaultValue([])
                ->__set('translateChoice', 'Person')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('person_admin.residency_status');

        $setting->setName('person_admin.residency_status')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'Residency Status')
            ->__set('description', 'List of residency status available in system.');
        if (empty($setting->getValue())) {
            $setting->setValue([
                'citizen',
                'permanent',
                'temporary',
                'visitor',
                'work',
            ])->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue([])
                ->__set('translateChoice', 'Person')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('departure.reasons');

        $setting->setName('departure.reasons')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'Departure Reasons')
            ->__set('description', 'List of departure reasons.');
        if (empty($setting->getValue())) {
            $setting->setValue([])
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue([])
                ->__set('translateChoice', 'Person')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Field Values';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('personnel.privacy');

        $setting->setName('personnel.privacy')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('boolean')
            ->__set('displayName', 'Privacy')
            ->__set('description', 'Should privacy options be turned on across the system?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->setValidators(null)
                ->setDefaultValue(false)
            ;
        }
        $setting->setHideParent('personnel.privacy');
        $settings[] = $setting;

        $setting = $sm->createOneByName('personnel.privacy_blurb');

        $setting->setName('personnel.privacy_blurb')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('text')
            ->__set('displayName', 'Privacy Blurb')
            ->__set('description', 'Descriptive text to accompany image privacy option when shown to people using the system.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
            ;
        }
        $setting->setHideParent('personnel.privacy');
        $settings[] = $setting;

        $setting = $sm->createOneByName('personnel.privacy_options');

        $setting->setName('personnel.privacy_options')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'Privacy Options')
            ->__set('description', 'List of choices to make available if privacy is turned on. If blank, privacy fields will not be displayed.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue([])
            ;
        }
        $setting->setHideParent('personnel.privacy');
        $settings[] = $setting;

        $section['name'] = 'Privacy Options';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('personal.background');

        $setting->setName('personal.background')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('boolean')
            ->__set('displayName', 'Personal Background')
            ->__set('description', 'Should personnel/students be allowed to set their own personal backgrounds?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->setValidators(null)
                ->setDefaultValue(true)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Interface Options';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('day_type_options');

        $setting->setName('day_type_options')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('array')
            ->__set('displayName', 'Day Type Options')
            ->__set('description', 'List of options to make available (e.g. half-day, full-day). If blank, this field will not show up in the application form.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('day_type_text');

        $setting->setName('day_type_text')
            ->__set('role', 'ROLE_ADMIN')
            ->setType('text')
            ->__set('displayName', 'Day-Type Text')
            ->__set('description', 'Explanatory text to include with Day Type Options.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->setValidators(null)
                ->setDefaultValue(null)
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Day Type Options';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_personal_settings';

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
