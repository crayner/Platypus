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
 * Date: 25/09/2018
 * Time: 12:08
 */
namespace App\Controller;

use App\Form\Type\TimetableType;
use App\Manager\TimetableDayManager;
use App\Manager\TimetableManager;
use App\Manager\TwigManager;
use App\Pagination\TimetablePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TimetableController
 * @package App\Controller
 */
class TimetableController extends Controller
{
    /**
     * manage
     *
     * @param TimetablePagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/timetable/list/", name="manage_timetables")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manage(TimetablePagination $pagination)
    {
        return $this->render('Timetable/list.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * edit
     *
     * @param TimetableManager $manager
     * @param Request $request
     * @param $id
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/timetable/{id}/edit/{tabName}", name="edit_timetable")
     * @Security("is_granted('USE_ROUTE', ['manage_timetables'])")
     */
    public function edit(TimetableManager $manager, Request $request, $id, $tabName = 'details')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(TimetableType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
        }

        return $this->render(
            'Timetable/edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'manager' => $manager,
            ]
        );
    }

    /**
     * delete
     *
     * @param TimetableManager $manager
     * @param TimetablePagination $pagination
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/timetable/{id}/delete/", name="delete_timetable")
     * @Security("is_granted('USE_ROUTE', ['manage_timetables'])")
     */
    public function delete(TimetableManager $manager, TimetablePagination $pagination, int $id)
    {
        $manager->delete($id);

        return new JsonResponse(
            [
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($this->get('translator')),
                'rows' => $pagination->getAllResults(),
            ],
            200
        );
    }

    /**
     * deleteDay
     *
     * @param TimetableDayManager $manager
     * @param int $id
     * @param $cid
     * @return JsonResponse
     * @throws \Exception
     * @Route("/timetable/{id}/day/{cid}/delete/", name="delete_timetable_day")
     * @Security("is_granted('USE_ROUTE', ['manage_timetables'])")
     */
    public function deleteDay(TimetableDayManager $manager,  int $id, $cid, TwigManager $twig)
    {
        $manager->delete($cid);

        return new JsonResponse(
            [
                'message' => $manager->getMessageManager()->renderView($twig->getTwig()),
            ],
            200
        );
    }
}