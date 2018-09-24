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
 * Date: 8/06/2018
 * Time: 13:23
 */
namespace App\Controller;

use App\Demonstration\PeopleFixtures;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class PlatypusController extends Controller
{
    /**
     * home
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     */
    public function home()
    {
        $x = [
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
            ];
        return $this->render('home.html.twig', [
            'xxxx' => Yaml::dump($x),
        ]);
    }

    /**
     * test
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/test/")
     */
    public function test(){
        $logger = $this->get('monolog.logger.demonstration');
        $em = $this->getDoctrine()->getManager();
        $section = 'People';
        $logger->addInfo(sprintf('Section %s started.', $section));
        $load = new PeopleFixtures();
        $load->load($em, $logger);
        $logger->addInfo(sprintf('Section %s completed.', $section));

        return $this->redirectToRoute('home');
    }
}