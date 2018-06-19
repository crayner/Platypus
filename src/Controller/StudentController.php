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
 * Date: 19/06/2018
 * Time: 10:47
 */
namespace App\Controller;

use App\Manager\StudentNoteCategoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class StudentController
 * @package App\Controller
 */
class StudentController extends Controller
{
    /**
     * deleteStudentNoteCategory
     *
     * @param $cid
     * @param StudentNoteCategoryManager $studentNoteCategoryManager
     * @Route("/student/note/category/{cid}/delete/", name="remove_student_note_category")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function deleteStudentNoteCategory($cid, StudentNoteCategoryManager $studentNoteCategoryManager)
    {
        $studentNoteCategoryManager->remove($cid);

        return new JsonResponse(
            [
                'message' => $studentNoteCategoryManager->getMessages(),
            ],
            200);
    }
}