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
use App\Manager\FormManager;
use App\Manager\FormManagerInterface;
use App\Manager\TimetableColumnManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     *
     * @param TimetableColumnManager $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/test/", name="test")
     */
    public function test(TimetableColumnManager $manager, Request $request, FormManager $formManager)
    {
        $tc = $manager->find(34);

        $form = $this->createForm(TestType::class, $tc);

        $form->get('name')->addError(new FormError('This is an error test!', 'This is an error test template!', ['template' => '']));

        return $this->render('test.html.twig',
            [
                'form' => $form,
                'manager' => $manager,
            ]
        );
    }

    /**
     * testPost
     *
     * @param Request $request
     * @param FormManager $formManager
     * @param TimetableColumnManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/test/post/", name="test_post", methods={"POST"})
     */
    public function testPost(Request $request, FormManager $formManager, TimetableColumnManager $manager)
    {
        $tc = $manager->find(34);

        $form = $this->createForm(TestType::class, $tc);

        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isValid())
        {
            // Save Data here
        }

        return new JsonResponse(
           [
               'form' => $formManager->extractForm($form->createView()),
               'messages' => $formManager->getFormErrors($form),
           ],
           200);
    }
}