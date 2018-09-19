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
 * Date: 14/06/2018
 * Time: 12:08
 */
namespace App\Controller;

use App\Entity\AttendanceCode;
use App\Entity\DayOfWeek;
use App\Entity\Facility;
use App\Entity\House;
use App\Entity\YearGroup;
use App\Form\AttendanceSettingsType;
use App\Form\CollectionManagerType;
use App\Form\DaysOfWeekType;
use App\Form\DepartmentType;
use App\Form\ExternalAssessmentFieldType;
use App\Form\ExternalAssessmentType;
use App\Form\FacilityType;
use App\Form\HousesType;
use App\Form\RollGroupType;
use App\Form\ScaleType;
use App\Form\SectionSettingType;
use App\Form\YearGroupType;
use App\Manager\AttendanceCodeManager;
use App\Manager\CollectionManager;
use App\Manager\DepartmentManager;
use App\Manager\ExternalAssessmentCategoryManager;
use App\Manager\ExternalAssessmentFieldManager;
use App\Manager\ExternalAssessmentManager;
use App\Manager\FlashBagManager;
use App\Manager\HouseManager;
use App\Manager\MultipleSettingManager;
use App\Manager\RollGroupManager;
use App\Manager\ScaleGradeManager;
use App\Manager\ScaleManager;
use App\Manager\SettingManager;
use App\Manager\TwigManager;
use App\Organism\AttendanceCodes;
use App\Organism\DaysOfWeek;
use App\Pagination\DepartmentPagination;
use App\Pagination\ExternalAssessmentPagination;
use App\Pagination\FacilityPagination;
use App\Pagination\RollGroupPagination;
use App\Pagination\ScalePagination;
use App\Util\PhotoHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    /**
     * daysOfWeek
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/school/year/days/of/week/", name="days_of_week_manage")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function daysOfWeek(Request $request, EntityManagerInterface $entityManager)
    {
        $data = new DaysOfWeek();

        $days = $entityManager->getRepository(DayOfWeek::class)->findBy([], ['sequence' => 'ASC']);
        $data->setDays(new ArrayCollection($days));

        $form = $this->createForm(DaysOfWeekType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($form->get('days')->getData()->toArray() as $day)
                $entityManager->persist($day);
            $entityManager->flush();
        }

        return $this->render('School/days_of_week.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * editHouses
     *
     * @param Request $request
     * @param HouseManager $houseManager
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/school/houses/manage/", name="houses_edit")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function editHouses(Request $request, HouseManager $houseManager, EntityManagerInterface $entityManager)
    {
        $houses = $entityManager->getRepository(House::class)->findBy([], ['name' => 'ASC']);
        $houseManager->setHouses(new ArrayCollection($houses));

        $form = $this->createForm(HousesType::class, $houseManager, ['deletePhoto' => $this->generateUrl('house_logo_delete', ['houseName' => '__imageDelete__'])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($form->get('houses')->getData()->toArray() as $house)
                $entityManager->persist($house);
            $entityManager->flush();
        }

        return $this->render('School/houses.html.twig',
            [
                'form'     => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * deleteHouseLogo
     *
     * @param $houseName
     * @param HouseManager $houseManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/school/houses/logo/{houseName}/delete/", name="house_logo_delete")
     * @Security("is_granted('USE_ROUTE', ['houses_edit'])")
     */
    public function deleteHouseLogo($houseName, HouseManager $houseManager)
    {
        $house = $houseManager->getEntityManager()->getRepository(House::class)->findOneBy(['name' => $houseName]);
        if ($house instanceof House)
            $house->setLogo(null);
        $houseManager->getEntityManager()->persist($house);
        $houseManager->getEntityManager()->flush();

        return $this->redirectToRoute('houses_edit');
    }

    /**
     * deleteHouse
     *
     * @param HouseManager $houseManager
     * @param string $cid
     * @return Response
     * @Route("/school/house/{cid}/delete/", name="house_remove")
     * @Security("is_granted('USE_ROUTE', ['houses_edit'])")
     */
    public function deleteHouse(HouseManager $houseManager, $cid = 'ignore')
    {
        $houseManager->removeHouse($houseManager->find($cid));

        return $this->forward(SchoolController::class.'::editHouses');
    }

    /**
     * editYearGroups
     *
     * @param Request $request
     * @param CollectionManager $collectionManager
     * @return Response
     * @Route("/school/year/groups/manage/", name="year_groups_manage")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function editYearGroups(Request $request, CollectionManager $collectionManager)
    {
        $yearGroups = $collectionManager->getEntityManager()->getRepository(YearGroup::class)->findBy([], ['sequence' => 'ASC']);
        $collectionManager->setCollection(new ArrayCollection($yearGroups));

        $form = $this->createForm(CollectionManagerType::class, $collectionManager,
            [
                'entry_type' => YearGroupType::class,
                'entry_options_data_class' => YearGroup::class,
                'translation_domain' => 'School',
                'sort_manage' => true,
                'button_merge_class' => 'btn-sm',
                'redirect_route' => 'year_group_remove'
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($form->get('collection')->getData()->toArray() as $yearGroup)
                $collectionManager->getEntityManager()->persist($yearGroup);
            $collectionManager->getEntityManager()->flush();
        }

        return $this->render('School/year_groups.html.twig',
            [
                'form'     => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * deleteYearGroup
     *
     * @param EntityManagerInterface $entityManager
     * @param string $cid
     * @return Response
     * @Route("/school/year/groups/{cid}/delete/", name="year_group_remove")
     * @Security("is_granted('USE_ROUTE', ['year_groups_manage'])")
     */
    public function deleteYearGroup(EntityManagerInterface $entityManager, $cid = 'ignore')
    {
        $yg = $entityManager->getRepository(YearGroup::class)->find($cid);

        if ($yg instanceof YearGroup)
        {
            $entityManager->remove($yg);
            $entityManager->flush();
        }

        return $this->forward(SchoolController::class.'::editYearGroups');
    }


    /**
     * attendanceSettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @param AttendanceCodeManager $manager
     * @return Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/school/attendance/settings/manage/", name="manage_attendance_settings")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function attendanceSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager, AttendanceCodeManager $manager)
    {
        $settings = new AttendanceCodes();
        $results = new ArrayCollection($sm->getEntityManager()->getRepository(AttendanceCode::class)->findBy([], ['sequence' => 'ASC']));
        $settings->setAttendanceCodes($results);
        foreach ($sm->createSettingDefinition('Attendance')->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $settings->setMultipleSettings($multipleSettingManager);

        $form = $this->createForm(AttendanceSettingsType::class, $settings);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('attendance_settings'));
            foreach($settings->getAttendanceCodes()->toArray() as $entity)
                $sm->getEntityManager()->persist($entity);
            $sm->getEntityManager()->flush();
        }

        return $this->render('School/attendance_settings.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * deleteAttendanceCode
     *
     * @param $cid
     * @param AttendanceCodeManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/school/attendance/code/{cid}/delete/", name="remove_attendance_code")
     * @Security("is_granted('USE_ROUTE', ['manage_attendance_settings'])")
     */
    public function deleteAttendanceCode($cid, AttendanceCodeManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->remove($cid);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('manage_attendance_settings');
    }

    /**
     * facilityList
     *
     * @param Request $request
     * @param FacilityPagination $pagination
     * @return Response
     * @Route("/school/facility/list/", name="manage_facilities")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function facilityList(Request $request, FacilityPagination $pagination)
    {
        $pagination->injectRequest($request);

        $pagination->getDataSet();

        return $this->render('School/facilities.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * editFacility
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/school/facility/{id}/edit/", name="facility_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_facilities'])")
     */
    public function editFacility($id, Request $request)
    {
        $facility = new Facility();

        if (intval($id) > 0)
            $facility = $this->getDoctrine()->getManager()->getRepository(Facility::class)->find($id);

        $facility->cancelURL = $this->get('router')->generate('manage_facilities');

        $form = $this->createForm(FacilityType::class, $facility);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->get('doctrine')->getManager();

            $em->persist($facility);
            $em->flush();

            if ($id === 'Add')
                return $this->redirectToRoute('facility_edit', ['id' => $facility->getId()]);
        }

        return $this->render('School/facilityEdit.html.twig', ['id' => $id, 'form' => $form->createView()]);
    }

    /**
     * duplicateFacility
     *
     * @param Request $request
     * @return Response
     * @Route("/school/facility/duplicate/", name="facility_duplicate")
     * @Security("is_granted('USE_ROUTE', ['manage_facilities'])")
     */
    public function duplicateFacility(Request $request)
    {
        $id = $request->get('facility')['duplicateid'];

        if ($id === "Add")
            $facility = new Facility();
        else
            $facility = $this->getDoctrine()->getManager()->getRepository(Facility::class)->find($id);

        $facility->cancelURL = $this->generateUrl('manage_facilities');

        $form = $this->createForm(FacilityType::class, $facility);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->get('doctrine')->getManager();

            $em->persist($facility);
            $em->flush();

            $route = $this->generateUrl('facility_edit', ['id' => 'Add']);
            $facility->setId(null);
            $facility->setName(null);
            $form = $this->createForm(FacilityType::class, $facility, ['action' => $route]);
            $id   = 'Add';
        }


        return $this->render('School/facilityEdit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * rollGroupManage
     *
     * @param RollGroupPagination $pagination
     * @param RollGroupManager $manager
     * @return Response
     * @Route("/school/roll/group/manage/", name="manage_roll_groups")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function rollGroupManage(RollGroupPagination $pagination, RollGroupManager $manager)
    {
        return $this->render('Pagination/list.html.twig',
            [
                'pagination' => $pagination,
                'manager' => $manager,
            ]
        );
    }

    /**
     * rollEdit
     *
     * @param Request $request
     * @param RollGroupManager $manager
     * @param string $id
     * @param string|null $closeWindow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/school/roll/group/{id}/edit/", name="roll_group_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_roll_groups'])")
     */
    public function rollEdit(Request $request, RollGroupManager $manager, $id = 'Add')
    {
        $roll = $manager->find($id);

        $form = $this->createForm(RollGroupType::class, $roll);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($roll);
            $manager->getEntityManager()->flush();

            if ($id === 'Add')
                return $this->redirectToRoute('roll_group_edit', ['id' => $roll->getId()]);
        }

        return $this->render('School/roll_group_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
                'headerOff' => true,
                'fullPage' => true,
            ]
        );
    }

    /**
     * rollDelete
     *
     * @param RollGroupManager $manager
     * @param FlashBagManager $flashBagManager
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/roll/group/{id}/delete/", name="roll_group_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_roll_groups'])")
     */
    public function rollDelete(RollGroupManager $manager, FlashBagManager $flashBagManager, $id = 'Add')
    {
        $manager->delete($id);
        $flashBagManager->addMessages($manager->getMessageManager());
        return $this->redirectToRoute('manage_roll_groups');
    }

    /**
     * rollCopy
     *
     * @param RollGroupManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @Route("/school/roll/groups/copy/", name="roll_group_copy_to_next_year")
     * @Security("is_granted('USE_ROUTE', ['manage_roll_groups'])")
     */
    public function rollCopy(RollGroupManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->copyToNextYear();
        $flashBagManager->addMessages($manager->getMessageManager());
        return $this->redirectToRoute('manage_roll_groups');
    }

    /**
     * departmentSettings
     *
     * @param Request $request
     * @param DepartmentPagination $pagination
     * @param DepartmentManager $manager
     * @param MultipleSettingManager $multipleSettingManager
     * @param SettingManager $sm
     * @return Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/school/departments/manage/", name="manage_departments")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function departmentSettings(Request $request, DepartmentPagination $pagination, DepartmentManager $manager,
                                       MultipleSettingManager $multipleSettingManager,  SettingManager $sm)
    {
        foreach($sm->createSettingDefinition('Department')->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('section'));
        }

        return $this->render('School/department_settings.html.twig',
            [
                'pagination' => $pagination,
                'manager' => $manager,
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * edit
     *
     * @param $id
     * @param Request $request
     * @param FlashBagManager $flashBagManager
     * @param DepartmentManager $departmentManager
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/school/department/{id}/edit/{tabName}/", name="department_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_departments'])")
     */
    public function edit($id, Request $request, FlashBagManager $flashBagManager, DepartmentManager $departmentManager, $tabName = 'department_details')
    {
        $entity = $departmentManager->findDepartment($id);

        $form = $this->createForm(DepartmentType::class, $entity, ['deletePhoto' => $this->generateUrl('department_logo_delete', ['id' => $id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $departmentManager->getEntityManager();
            $em->persist($entity);

            $flashBagManager->add('success', 'form.submit.success', [], 'System');

            if ($id == 'Add')
            {
                $count = 0;
                foreach ($entity->getMembers()->toArray() as $member)
                {
                    $member->addDepartment($entity);

                    $em->persist($member);
                    $count++;
                }
                $em->flush();

                if ($count > 0)
                    $flashBagManager->add('success', 'department.member.added.success', [], 'School');
                $flashBagManager->addMessages();

                return $this->redirectToRoute('department_edit', ['id' => $entity->getId(), 'tabName' => $tabName]);
            }
            $em->flush();

            $form = $this->createForm(DepartmentType::class, $entity, ['deletePhoto' => $this->generateUrl('department_logo_delete', ['id' => $id])]);

        }

        $flashBagManager->addMessages();

        return $this->render('School/department_edit.html.twig', [
                'form'      => $form->createView(),
                'fullForm'  => $form,
                'tabManager' => $departmentManager,
            ]
        );
    }

    /**
     * deleteLogo
     *
     * @param $id
     * @param DepartmentManager $departmentManager
     * @param FlashBagManager $flashBagManager
     * @return Response
     * @Route("/school/department/logo/delete/{id}/", name="department_logo_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_departments'])")
     */
    public function deleteLogo($id, DepartmentManager $departmentManager, FlashBagManager $flashBagManager)
    {
        $departmentManager->removeLogo($id);
        $flashBagManager->addMessages($departmentManager->getMessageManager());
/*
        $om     = $this->getDoctrine()->getManager();
        $entity = $om->getRepository(Department::class)->find($id);

        if ($entity instanceof Department)
        {
            $file = $entity->getLogo();
            if (file_exists($file))
                unlink($file);

            $entity->setLogo(null);
            $om->persist($entity);
            $om->flush();
        }
*/
        return $this->forward(SchoolController::class.'::edit', ['id' => $id]);
    }

    /**
     * manageMemberCollection
     *
     * @param $id
     * @param DepartmentManager $departmentManager
     * @param TwigManager $twig
     * @param string $cid
     * @return JsonResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/school/department/{id}/members/{cid}/manage/", name="department_members_manage")
     * @Security("is_granted('USE_ROUTE', ['manage_departments'])")
     */
    public function manageMemberCollection($id, DepartmentManager $departmentManager, TwigManager $twig, $cid = 'ignore')
    {
        $departmentManager->removeMember($id, $cid);

        $form = $this->createForm(DepartmentType::class, $departmentManager->refreshDepartment(), ['deletePhoto' => $this->generateUrl('department_logo_delete', ['id' => $id])]);

        $collection = $form->has('members') ? $form->get('members')->createView() : null;

        if (empty($collection))
            $departmentManager->getMessageManager()->add('warning', 'department.members.not_defined');

        $content = $this->renderView("School/department_collection.html.twig",
            [
                'collection'    => $collection,
                'route'         => 'department_members_manage',
                'contentTarget' => 'department_members_target',
            ]
        );

        return new JsonResponse(
            [
                'content' => $content,
                'status'  => $departmentManager->getMessageManager()->getStatus(),
                'message' => $departmentManager->getMessageManager()->renderView($twig->getTwig()),
            ],
            200
        );
    }

    /**
     * deleteDepartment
     *
     * @param $id
     * @param DepartmentManager $departmentManager
     * @param FlashBagManager $flashBagManager
     * @return Response
     * @throws \Exception
     * @Route("/school/department/{id}/delete/", name="department_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_departments'])")
     */
    public function deleteDepartment($id, DepartmentManager $departmentManager, FlashBagManager $flashBagManager)
    {
        $departmentManager->delete($id);
        $flashBagManager->addMessages($departmentManager->getMessageManager());
        return $this->forward(SchoolController::class.'::departmentSettings');
    }

    /**
     * scaleManage
     *
     * @param Request $request
     * @param ScalePagination $pagination
     * @param ScaleManager $manager
     * @return Response
     * @Route("/school/scale/manage/", name="manage_scales")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function scaleManage(Request $request, ScalePagination $pagination, ScaleManager $manager)
    {
        $pagination->injectRequest($request);

        $pagination->getDataSet();

        return $this->render('School/scale_list.html.twig',
            [
                'pagination' => $pagination,
                'manager' => $manager,
            ]
        );
    }

    /**
     * scaleEdit
     *
     * @param Request $request
     * @param ScaleManager $manager
     * @param string $id
     * @param string $tabName
     * @param string|null $closeWindow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/school/scale/{id}/edit/{tabName}/{closeWindow}", name="scale_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_scales'])")
     */
    public function scaleEdit(Request $request, ScaleManager $manager, $id = 'Add', $tabName = 'details', string $closeWindow = null)
    {
        $scale = $manager->find($id);

        $form = $this->createForm(ScaleType::class, $scale);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($scale);
            $manager->getEntityManager()->flush();

            return $this->redirectToRoute('scale_edit', ['id' => $scale->getId(), 'tabName' => $tabName, 'closeWindow' => $closeWindow]);
        }

        return $this->render('School/scale_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
            ]
        );
    }

    /**
     * scaleDelete
     *
     * @param $id
     * @param ScaleManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/scale/{id}/delete/", name="scale_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_scales'])")
     */
    public function scaleDelete($id, ScaleManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->delete($id);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('manage_scales');
    }

    /**
     * scaleGradeDelete
     *
     * @param $cid
     * @param ScaleGradeManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/scale/grade/{cid}/delete/", name="scale_grade_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_scales'])")
     */
    public function scaleGradeDelete($cid, ScaleGradeManager $manager, FlashBagManager $flashBagManager)
    {
        $grade = $manager->find($cid);
        $manager->delete($cid);
        $flashBagManager->addMessages($manager->getMessageManager());
        if ($grade)
            return $this->redirectToRoute('scale_edit', ['id' => $grade->getScale()->getId(), 'tabName' => 'scale_grade_collection']);
        else
            return $this->redirectToRoute('manage_scales');
    }

    /**
     * manageExternalAssessments
     *
     * @param Request $request
     * @param ExternalAssessmentPagination $pagination
     * @return Response
     * @Route("/school/manage/external/assessments", name="manage_external_assessments")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manageExternalAssessments(Request $request, ExternalAssessmentPagination $pagination)
    {
        $pagination->injectRequest($request);

        $pagination->getDataSet();

        return $this->render('School/external_assessment_list.html.twig',
            [
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * editExternalAssessment
     *
     * @param Request $request
     * @param ExternalAssessmentManager $manager
     * @param string $id
     * @param string $tabName
     * @param string|null $closeWindow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/school/external/assessment/{id}/edit/{tabName}/{closeWindow}", name="external_assessment_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_external_assessments'])")
     */
    public function editExternalAssessment(Request $request, ExternalAssessmentManager $manager, $id = 'Add', string $tabName = 'details', string $closeWindow = null)
    {
        $externalAssessment = $manager->find($id);

        $form = $this->createForm(ExternalAssessmentType::class, $externalAssessment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($externalAssessment);
            $manager->getEntityManager()->flush();

            return $this->redirectToRoute('external_assessment_edit', ['id' => $externalAssessment->getId(), 'tabName' => $tabName, 'closeWindow' => $closeWindow]);
        }

        return $this->render('School/external_assessment_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
            ]
        );
    }

    /**
     * deleteExternalAssessment
     *
     * @param int $id
     * @param ExternalAssessmentManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/external/assessment/{id}/delete/", name="external_assessment_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_external_assessments'])")
     */
    public function deleteExternalAssessment(int $id, ExternalAssessmentManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->delete($id);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('manage_external_assessments');
    }

    /**
     * editExternalAssessmentField
     *
     * @param Request $request
     * @param int $ea
     * @param ExternalAssessmentFieldManager $manager
     * @param ExternalAssessmentManager $externalAssessmentManager
     * @param string $id
     * @param string $tabName
     * @param string|null $closeWindow
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     * @Route("/school/external/assessment/{ea}/field/{id}/edit/{tabName}/{closeWindow}", name="external_assessment_field_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_external_assessments'])")
     */
    public function editExternalAssessmentField(Request $request, int $ea,
                                                ExternalAssessmentFieldManager $manager, ExternalAssessmentManager $externalAssessmentManager, $id = 'Add', string $tabName = 'details', string $closeWindow = null)
    {
        $externalAssessmentField = $manager->find($id);

        if ($id === 'Add')
            $externalAssessmentField->setExternalAssessment($externalAssessmentManager->find($ea));

        $form = $this->createForm(ExternalAssessmentFieldType::class, $externalAssessmentField);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($externalAssessmentField);
            $manager->getEntityManager()->flush();

            return $this->redirectToRoute('external_assessment_field_edit', ['id' => $externalAssessmentField->getId(), 'ea' => $ea, 'tabName' => $tabName, 'closeWindow' => $closeWindow]);
        }

        return $this->render('School/external_assessment_field_edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'tabManager' => $manager,
            ]
        );
    }

    /**
     * deleteExternalAssessmentField
     *
     * @param int $id
     * @param ExternalAssessmentFieldManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/external/assessment/field/{id}/delete/", name="external_assessment_field_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_external_assessments'])")
     */
    public function deleteExternalAssessmentField(int $id, ExternalAssessmentFieldManager $manager, FlashBagManager $flashBagManager)
    {
        $eaf = $manager->find($id);
        $manager->delete($id);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('external_assessment_edit', ['id' => $eaf->getExternalAssessment()->getId(), 'tabName' => 'fields']);
    }

    /**
     * deleteExternalAssessmentCategory
     *
     * @param int $id
     * @param ExternalAssessmentCategoryManager $manager
     * @param FlashBagManager $flashBagManager
     * @param string $cid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/school/external/assessment/{id}/category/{cid}/delete/", name="external_assessment_category_delete")
     * @Security("is_granted('USE_ROUTE', ['manage_external_assessments'])")
     */
    public function deleteExternalAssessmentCategory(int $id, ExternalAssessmentCategoryManager $manager, FlashBagManager $flashBagManager, $cid = 'ignore')
    {
        $manager->delete($cid);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('external_assessment_edit', ['id' => $id, 'tabName' => 'categories']);
    }
}