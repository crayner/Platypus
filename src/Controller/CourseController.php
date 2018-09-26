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
 * Date: 20/09/2018
 * Time: 21:35
 */
namespace App\Controller;

use App\Form\Type\CourseClassType;
use App\Form\Type\CourseType;
use App\Manager\CourseClassManager;
use App\Manager\CourseManager;
use App\Manager\TwigManager;
use App\Pagination\CoursePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CourseController
 * @package App\Controller
 */
class CourseController extends Controller
{
    /**
     * manage
     *
     * @param CoursePagination $pagination
     * @param CourseManager $manager
     * @return mixed
     * @Route("/courses/manage/", name="manage_courses")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manage(CoursePagination $pagination, CourseManager $manager)
    {
        return $this->render('Course/list.html.twig',
            array(
                'pagination' => $pagination,
                'manager'    => $manager,
            )
        );
    }

    /**
     * edit
     *
     * @param CourseManager $manager
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/course/{id}/edit/{tabName}", name="edit_course")
     * @Security("is_granted('USE_ROUTE', ['manage_courses'])")
     */
    public function edit(CourseManager $manager, Request $request, $id, $tabName = 'details')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(CourseType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
            $form = $this->createForm(CourseType::class, $entity);
        }

        return $this->render(
            'Course/edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'manager' => $manager,
            ]
        );
    }

    /**
     * editClass
     *
     * @param CourseClassManager $manager
     * @param Request $request
     * @param $id
     * @param string $tabName
     * @Route("/course/{course_id}/class/{id}/edit/{tabName}", name="edit_class")
     * @Security("is_granted('USE_ROUTE', ['manage_courses'])")
     */
    public function editClass(CourseClassManager $manager, Request $request, $course_id, $id, $tabName = 'details')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(CourseClassType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
            $form = $this->createForm(CourseClassType::class, $entity);
        }

        return $this->render(
            'Course/class_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'manager' => $manager,
            ]
        );
    }

    /**
     * removeClassParticipant
     *
     * @param CourseClassManager $manager
     * @param int $id
     * @param mixed $cid
     * @param TwigManager $twig
     * @return JsonResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/class/{id}/participant/{cid}/remove/", name="remove_class_participant")
     * @Security("is_granted('USE_ROUTE', ['manage_courses'])")
     */
    public function removeClassParticipant(CourseClassManager $manager, int $id, $cid, TwigManager $twig)
    {
        $manager->find($id);

        $manager->removeClassParticipant($cid);

        return new JsonResponse([
            'message' => $manager->getMessageManager()->renderView($twig->getTwig()),
        ],200);
    }
}
