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

use App\Pagination\TimetablePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
}