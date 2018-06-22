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
use App\Form\FacilityType;
use App\Form\HousesType;
use App\Form\YearGroupType;
use App\Manager\AttendanceCodeManager;
use App\Manager\CollectionManager;
use App\Manager\FlashBagManager;
use App\Manager\HouseManager;
use App\Manager\MultipleSettingManager;
use App\Manager\SettingManager;
use App\Organism\AttendanceCodes;
use App\Organism\DaysOfWeek;
use App\Pagination\FacilityPagination;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @Route("/school/year/days/of/week/", name="days_of_week_manage")
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
     * @Route("/school/houses/manage/", name="houses_edit")
     * @IsGranted("ROLE_REGISTRAR")
     * @param Request $request
     * @param HouseManager $houseManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @IsGranted("ROLE_REGISTRAR")
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
     * @param $houseName
     * @param HouseManager $houseManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/school/houses/logo/{houseName}/delete/", name="house_logo_delete")
     * @IsGranted("ROLE_REGISTRAR")
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
     * @Route("/school/house/{cid}/delete/", name="house_remove")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function deleteHouse($cid = 'ignore', HouseManager $houseManager)
    {
        $houseManager->removeHouse($houseManager->find($cid));

        return $this->forward(SchoolController::class.'::editHouses');
    }

    /**
     * editYearGroups
     *
     * @param Request $request
     * @param CollectionManager $collectionManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/school/year/groups/manage/", name="year_groups_manage")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function editYearGroups(Request $request, CollectionManager $collectionManager)
    {
        $yearGroups = $collectionManager->getEntityManager()->getRepository(YearGroup::class)->findBy([], ['sequence' => 'ASC']);
        $collectionManager->setCollection(new ArrayCollection($yearGroups));

        $form = $this->createForm(CollectionManagerType::class, $collectionManager,
            [   'entry_type' => YearGroupType::class,
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
     * @Route("/school/year/groups/{cid}/delete/", name="year_group_remove")
     * @IsGranted("ROLE_REGISTRAR")
     * @param string $cid
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteYearGroup($cid = 'ignore', EntityManagerInterface $entityManager)
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
     * Student Settings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @param AttendanceCodeManager $manager  (Force load to ensure static methods are available.)
     * @return Response
     * @Route("/school/attendance/settings/manage/", name="manage_attendance_settings")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function attendanceSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager, AttendanceCodeManager $manager)
    {
        $settings = new AttendanceCodes();
        $results = new ArrayCollection($sm->getEntityManager()->getRepository(AttendanceCode::class)->findBy([], ['sequence' => 'ASC']));
        $settings->setAttendanceCodes($results);
        foreach ($sm->createSettingDefinition('Attendance') as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);
        $settings->setMultipleSettings($multipleSettingManager);

        $form = $this->createForm(AttendanceSettingsType::class, $settings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm);
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
     * deleteStudentNoteCategory
     *
     * @Route("/school/attendance/code/{cid}/delete/", name="remove_attendance_code")
     * @IsGranted("ROLE_PRINCIPAL")
     * @param $cid
     * @param AttendanceCodeManager $manager
     * @param FlashBagManager $flashBagManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAttendanceCode($cid, AttendanceCodeManager $manager, FlashBagManager $flashBagManager)
    {
        $manager->remove($cid);
        $flashBagManager->addMessages($manager->getMessageManager());

        return $this->redirectToRoute('manage_attendance_settings');
    }

    /**
     * @Route("/school/facility/list/", name="manage_facilities")
     * @IsGranted("ROLE_REGISTRAR")
     * @param Request         $request
     * @param FacilityPagination $pagination
     *
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @Route("/school/facility/{id}/edit/", name="facility_edit")
     * @IsGranted("ROLE_REGISTRAR")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
     * @Route("/school/facility/duplicate/", name="facility_duplicate")
     * @IsGranted("ROLE_REGISTRAR")
     * @param Request $request
     * @return Response
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
}