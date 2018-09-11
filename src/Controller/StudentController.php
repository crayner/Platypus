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

use App\Entity\StudentNoteCategory;
use App\Form\StudentsSettingsType;
use App\Manager\MultipleSettingManager;
use App\Manager\SettingManager;
use App\Manager\StudentNoteCategoryManager;
use App\Organism\StudentNoteCategories;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * studentSettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @return Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/setting/student/management/", name="manage_student_settings")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function studentSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager)
    {
        $categories = new StudentNoteCategories();
        $notes = new ArrayCollection($sm->getEntityManager()->getRepository(StudentNoteCategory::class)->findBy([], ['name' => 'ASC']));
        $categories->setCategories($notes);
        foreach ($sm->createSettingDefinition('Students')->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);
        $categories->setMultipleSettings($multipleSettingManager);

        $form = $this->createForm(StudentsSettingsType::class, $categories);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('students_settings')['multipleSettings']);
            foreach($categories->getCategories()->toArray() as $snc)
                $sm->getEntityManager()->persist($snc);
            $sm->getEntityManager()->flush();
        }

        return $this->render('Setting/student_settings.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }
}