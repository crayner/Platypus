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
 * Date: 10/11/2018
 * Time: 10:23
 */
namespace App\Controller;

use App\Entity\CourseClass;
use App\Manager\CourseClassManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AttendanceController
 * @package App\Controller
 */
class AttendanceController extends Controller
{
    /**
     * attendanceByClass
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/attendance/class/{entity}/", name="attendance_by_class")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function attendanceByClass(CourseClass $entity, CourseClassManager $manager)
    {

        $manager->setEntity($entity);
        return $this->render('blank.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }
}
