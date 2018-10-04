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

use App\Entity\SchoolYearTerm;
use App\Entity\Timetable;
use App\Entity\TimetableColumn;
use App\Entity\TimetableDayDate;
use App\Form\Type\TimetableColumnType;
use App\Form\Type\TimetableType;
use App\Manager\FlashBagManager;
use App\Manager\TimetableColumnManager;
use App\Manager\TimetableColumnRowManager;
use App\Manager\TimetableDayManager;
use App\Manager\TimetableManager;
use App\Manager\TwigManager;
use App\Pagination\TimetableColumnPagination;
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
    public function deleteDay(TimetableDayManager $manager, int $id, $cid, TwigManager $twig)
    {
        $manager->delete($cid);

        return new JsonResponse(
            [
                'message' => $manager->getMessageManager()->renderView($twig->getTwig()),
            ],
            200
        );
    }

    /**
     * listColumns
     *
     * @param TimetableColumnPagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/timetable/column/list/", name="manage_columns")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function listColumns(TimetableColumnPagination $pagination)
    {
        return $this->render('Timetable/column_list.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * deleteColumn
     *
     * @param TimetableColumnManager $manager
     * @param TimetableColumnPagination $pagination
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/timetable/column/{id}/delete/", name="delete_timetable")
     * @Security("is_granted('USE_ROUTE', ['manage_columns'])")
     */
    public function deleteColumn(TimetableColumnManager $manager, TimetableColumnPagination $pagination, int $id)
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
     * editColumn
     *
     * @param TimetableColumnManager $manager
     * @param Request $request
     * @param $id
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/timetable/column/{id}/edit/{tabName}", name="edit_column")
     * @Security("is_granted('USE_ROUTE', ['manage_columns'])")
     */
    public function editColumn(TimetableColumnManager $manager, Request $request, TimetableColumn $id, $tabName = 'details')
    {
        $manager->setEntity($id);

        $form = $this->createForm(TimetableColumnType::class, $manager->getEntity());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($manager->getEntity());
            $manager->getEntityManager()->flush();
        }

        return $this->render(
            'Timetable/column_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'manager' => $manager,
            ]
        );
    }

    /**
     * deleteColumnRow
     *
     * @param TimetableColumnRowManager $manager
     * @param int $id
     * @param $cid
     * @param FlashBagManager $bagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/timetable/column/{id}/row/{cid}/delete/", name="delete_timetable_column_row")
     * @Security("is_granted('USE_ROUTE', ['manage_columns'])")
     */
    public function deleteColumnRow(TimetableColumnRowManager $manager, TimetableColumn $id, $cid, FlashBagManager $bagManager)
    {
        $manager->delete($cid);

        $bagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('edit_column', ['id' => $id->getId(), 'tabName' => 'rows']);
    }

    /**
     * timetableDayIncrement
     *
     * @param Timetable $timetable
     * @param SchoolYearTerm $term
     * @param TimetableDayDate $dayDate
     * @param TimetableManager $manager
     * @Route("/timetable/{id}/term/{term}/day/date/{dayDate}/increment/", name="next_day_date")
     * @Security("is_granted('USE_ROUTE', ['manage_timetables'])")
     * @return JsonResponse
     */
    public function timetableDayIncrement(int $id, SchoolYearTerm $term, TimetableDayDate $dayDate, TimetableManager $manager)
    {
        $manager->find($id);
        $manager->nextDayDate($dayDate);
        return new JsonResponse(
            [
                'data' => $this->renderView('Timetable/assign_days_content.html.twig',
                    [
                        'term' => $term,
                        'manager' => $manager,
                    ]
                ),
                'messages' => [],
            ],
            200);
    }

    /**
     * timetableDayIncrement
     *
     * @param Timetable $timetable
     * @param SchoolYearTerm $term
     * @param TimetableDayDate $dayDate
     * @param TimetableManager $manager
     * @Route("/timetable/{id}/term/{term}/days/display/", name="display_term_dates")
     * @Security("is_granted('USE_ROUTE', ['manage_timetables'])")
     * @return JsonResponse
     */
    public function displayTermDays(int $id, SchoolYearTerm $term, TimetableManager $manager)
    {
        $manager->find($id);
        return new JsonResponse(
            [
                'data' => $this->renderView('Timetable/assign_days_content.html.twig',
                    [
                        'term' => $term,
                        'manager' => $manager,
                    ]
                ),
            ],
            200);
    }
}