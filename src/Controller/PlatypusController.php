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
        return $this->render('home.html.twig');
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