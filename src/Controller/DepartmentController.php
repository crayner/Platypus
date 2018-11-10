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
 * Date: 9/11/2018
 * Time: 11:15
 */
namespace App\Controller;

use App\Entity\CourseClass;
use App\Manager\CourseClassManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DepartmentController
 * @package App\Controller
 */
class DepartmentController extends Controller
{
    /**
     * courseClass
     *
     * @param CourseClass $entity
     * @param CourseClassManager $manager
     * @param TranslatorInterface $translator
     * @return Response
     * @Route("/department/course/class/{entity}/", name="course_class")
     * @Security("is_granted('USE_ROUTE', ['view_departments'])")
     */
    public function courseClass(CourseClass $entity, CourseClassManager $manager, TranslatorInterface $translator)
    {
        $manager->setTranslator($translator)->setEntity($entity);
        return $this->render('Department/course_class.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }

    /**
     * courseClass
     *
     * @Route("/department/view/", name="view_departments")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function view(){}
}