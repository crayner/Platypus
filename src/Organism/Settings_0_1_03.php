<?php
namespace App\Organism;

use App\Manager\SettingInterface;

class Settings_0_1_03 implements SettingInterface
{
	const VERSION = '0.1.03';

	/**
	 * @return string
	 */
	public function getSettings()
	{
		return <<<LLL
ethnicity.list:
    type: array
    name: ethnicity.list
    displayName: 'List of Ethnicities'
    description: 'List of Ethnicities.  Uses the Australian Standard to create this list'
    value:
        'Inadequately described': '0000'
        'Not stated': '0001'
        Eurasian: '0901'
        Asian: '0902'
        'African, so decribed': '0903'
        'European, so decribed': '0904'
        'Caucasian, so decribed': '0905'
        'Creole, so decribed': '0906'
        Oceanian: '1000'
        'Australian Peoples': '1100'
        'New Zealand Peoples': '1200'
        'Melanesian and Papuan': '1300'
        Micronesian: '1400'
        Polynesian: '1500'
        'North-West European': '2000'
        British: '2100'
        'Western European': '2300'
        'Northern European': '2400'
        'Southern and  Eastern European': '3000'
        'Southern European': '3100'
        'South Eastern European': '3200'
        'Eastern European': '3300'
        'North African and Middle Eastern': '4000'
        Arab: '4100'
        'Peoples of the Sudan': '4300'
        'Other North African and Middle Eastern': '4900'
        'South-East  Asian': '5000'
        'Mainland South-East Asian': '5100'
        'Maritime South-East Asian': '5200'
        'North-East Asian': '6000'
        'Chinese Asian': '6100'
        'Other North-East Asian': '6900'
        'Southern and Central Asian': '7000'
        'Southern Asian': '7100'
        'Central Asian': '7200'
        'Peoples of the Americas': '8000'
        'North American': '8100'
        'South American': '8200'
        'Central American': '8300'
        'Caribbean Islander': '8400'
        'Sub-Saharan African': '9000'
        'Central and West African': '9100'
        'Southern and East African': '9200'
    choice: null
    validators: null
    role: null
    defaultValue: {  }
    translateChoice: null
religion.list:
    type: array
    name: religion.list
    displayName: 'List of Religions'
    description: 'List of Religions.  Uses the Australian Standard to create this list'
    value:
        'Aboriginal Evangelical Missions': '2801'
        'Acts 2 Alliance': '2416'
        Agnosticism: '7201'
        'Albanian Orthodox': '2231'
        'Ancestor Veneration': '6051'
        'Ancient Church of the East': '2222'
        'Anglican Catholic Church': '2013'
        'Anglican Church of Australia': '2012'
        Animism: '6131'
        'Antiochian Orthodox': '2232'
        'Apostolic Church (Australia)': '2401'
        'Apostolic Church of Queensland': '2901'
        'Armenian Apostolic': '2212'
        'Assyrian Apostolic, nec': '2229'
        'Assyrian Church of the East': '2221'
        Atheism: '7202'
        'Australian Aboriginal Traditional Religions': '6011'
        'Australian Christian Churches (Assemblies of God)': '2402'
        'Baha''i': '6031'
        Baptist: '2031'
        'Bethesda Ministries International (Bethesda Churches)': '2403'
        'Born Again Christian': '2802'
        Brethren: '2051'
        Buddhism: '1011'
        'C3 Global (Christian City Church)': '2404'
        Caodaism: '6991'
        'Catholic, nec': '2079'
        'Chaldean Catholic': '2075'
        'Chinese Religions, nec': '6059'
        Christadelphians: '2902'
        'Christian and Missionary Alliance': '2803'
        'Christian Church in Australia': '2417'
        'Christian Community Churches of Australia': '2811'
        'Christian Science': '2903'
        'Church of Christ (Nondenominational)': '2112'
        'Church of Jesus Christ of Latter-day Saints': '2151'
        'Church of Scientology': '6992'
        'Church of the Nazarene': '2804'
        'Churches of Christ (Conference)': '2111'
        'Community of Christ': '2152'
        Confucianism: '6052'
        Congregational: '2805'
        'Coptic Orthodox': '2214'
        'CRC International (Christian Revival Crusade)': '2407'
        Druidism: '6132'
        Druse: '6071'
        'Eastern Orthodox, nec': '2239'
        Eckankar: '6993'
        'Ethiopian Orthodox': '2216'
        'Ethnic Evangelical Churches': '2806'
        'Foursquare Gospel Church': '2411'
        'Free Reformed': '2253'
        'Full Gospel Church of Australia (Full Gospel Church)': '2412'
        'Gnostic Christians': '2904'
        'Grace Communion International (Worldwide Church of God)': '2915'
        'Greek Orthodox': '2233'
        Hinduism: '3011'
        Humanism: '7203'
        'Independent Evangelical Churches': '2807'
        'International Church of Christ': '2113'
        'International Network of Churches (Christian Outreach Centres)': '2406'
        Islam: '4011'
        Jainism: '6997'
        'Japanese Religions, nec': '6119'
        'Jehovah''s Witnesses': '2131'
        Judaism: '5011'
        'Liberal Catholic Church': '2905'
        Lutheran: '2171'
        'Macedonian Orthodox': '2234'
        Mandaean: '6901'
        'Maronite Catholic': '2072'
        'Melkite Catholic': '2073'
        'Methodist, so described': '2812'
        'Multi Faith': '7301'
        'Nature Religions, nec': '6139'
        'New Age': '7302'
        'New Apostolic Church': '2906'
        'New Churches (Swedenborgian)': '2907'
        'No Religion, so described': '7101'
        'Oriental Orthodox, nec': '2219'
        'Other Anglican': '2019'
        'Other Christian, nec': '2999'
        'Other Protestant, nec': '2899'
        'Other Spiritual Beliefs, nec': '7399'
        'Own Spiritual Beliefs': '7303'
        Paganism: '6133'
        'Pentecostal City Life Church': '2418'
        'Pentecostal, nec': '2499'
        Presbyterian: '2251'
        Rastafari: '6994'
        'Ratana (Maori)': '2908'
        Rationalism: '7204'
        Reformed: '2252'
        'Religious Groups, nec': '6999'
        'Religious Science': '2911'
        'Religious Society of Friends (Quakers)': '2912'
        'Revival Centres': '2413'
        'Revival Fellowship': '2421'
        'Rhema Family Church': '2414'
        'Romanian Orthodox': '2235'
        'Russian Orthodox': '2236'
        'Salvation Army': '2271'
        Satanism: '6995'
        'Secular Beliefs nec': '7299'
        'Serbian Orthodox': '2237'
        'Seventh-day Adventist': '2311'
        Shinto: '6111'
        Sikhism: '6151'
        Spiritualism: '6171'
        'Sukyo Mahikari': '6112'
        'Syrian Orthodox': '2215'
        'Syro Malabar Catholic': '2076'
        Taoism: '6053'
        'Temple Society': '2913'
        Tenrikyo: '6113'
        Theism: '7304'
        Theosophy: '6996'
        'Ukrainian Catholic': '2074'
        'Ukrainian Orthodox': '2238'
        'United Methodist Church': '2813'
        'United Pentecostal': '2415'
        'Uniting Church': '2331'
        'Universal Unitarianism': '7305'
        'Victory Life Centre': '2422'
        'Victory Worship Centre': '2423'
        'Wesleyan Methodist Church': '2808'
        'Western Catholic': '2071'
        'Wiccan (Witchcraft)': '6135'
        'Worship Centre network': '2424'
        Yezidi: '6902'
        Zoroastrianism: '6998'
    choice: null
    validators: null
    role: ROLE_ADMIN
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