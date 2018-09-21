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

use App\Manager\CourseManager;
use App\Pagination\CoursePagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("courses/manage/", name="manage_courses")
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
}
