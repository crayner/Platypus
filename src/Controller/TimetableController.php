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
use App\Manager\TimetableManager;
use App\Pagination\TimetablePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
            $form = $this->createForm(TimetableType::class, $entity);
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
}