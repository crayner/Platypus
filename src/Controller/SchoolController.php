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

use App\Entity\DayOfWeek;
use App\Entity\House;
use App\Entity\YearGroup;
use App\Form\CollectionManagerType;
use App\Form\DaysOfWeekType;
use App\Form\HousesType;
use App\Form\YearGroupType;
use App\Manager\CollectionManager;
use App\Manager\FlashBagManager;
use App\Manager\HouseManager;
use App\Organism\DaysOfWeek;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
}