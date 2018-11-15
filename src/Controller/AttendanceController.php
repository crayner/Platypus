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
use App\Form\Type\AttendanceByClassType;
use App\Manager\AttendanceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AttendanceController
 * @package App\Controller
 */
class AttendanceController extends Controller
{
    /**
     * attendanceByClass
     *
     * @param AttendanceManager $manager
     * @param TranslatorInterface $translator
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param CourseClass|null $entity
     * @param string $date
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/attendance/class/{entity}/on/{date}/", name="attendance_by_class")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function attendanceByClass(AttendanceManager $manager, TranslatorInterface $translator, ValidatorInterface $validator, Request $request, CourseClass $entity = null, string $date = 'now')
    {
        $manager->setTranslator($translator)->takeAttendanceByClass($entity, $date);

        if ($request->getMethod() === 'POST' && $this->isCsrfTokenValid('attendanceByClass', $request->request->get('_token'))) {
            $manager->saveAttendanceLogs($request->get('attendanceByClass'), $validator);
            $manager->takeAttendanceByClass($entity, $date);
        }
        return $this->render('Attendance/take_by_class.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }
}
