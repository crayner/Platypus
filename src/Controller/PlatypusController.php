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
use App\Form\Type\TestType;
use App\Manager\TimetableColumnManager;
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
        return $this->render('home.html.twig');
    }

    /**
     * test
     * @Route("/test/", name="test")
     */
    public function test(TimetableColumnManager $manager)
    {
        $tc = $manager->find(33);

        $form = $this->createForm(TestType::class, $tc);
        return $this->render('test.html.twig',
            [
                'form' => $form,
                'manager' => $manager,
            ]
        );
    }
}