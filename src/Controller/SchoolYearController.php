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
 * Date: 12/06/2018
 * Time: 11:48
 */
namespace App\Controller;

use App\Entity\SchoolYear;
use App\Entity\SchoolYearSpecialDay;
use App\Entity\SchoolYearTerm;
use App\Form\SchoolYearType;
use App\Manager\FlashBagManager;
use App\Manager\MessageManager;
use App\Manager\SchoolYearManager;
use App\Manager\SettingManager;
use App\Util\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SchoolYearController extends Controller
{

    /**
     * schoolYearManage
     *
     * @param SchoolYearManager $schoolYearManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/school/year/manage/", name="school_year_manage")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function schoolYearManage(SchoolYearManager $schoolYearManager)
    {
        $schoolYears = $schoolYearManager->getSchoolYearRepository()->createQueryBuilder('y')
            ->orderBy('y.firstDay', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getArrayResult()
        ;

        return $this->render('SchoolYear/manage.html.twig',
            [
                'school_years' => $schoolYears,
                'manager' => $schoolYearManager,
            ]
        );

    }

    /**
     * edit
     *
     * @param $id
     * @param Request $request
     * @param SchoolYearManager $schoolYearManager
     * @param EntityManagerInterface $em
     * @param MessageManager $messageManager
     * @param FlashBagManager $flashBagManager
     * @param string $tabName
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/school/year/{id}/edit/{tabName}/", name="school_year_edit")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function edit($id, Request $request,
                         SchoolYearManager $schoolYearManager,
                         EntityManagerInterface $em, MessageManager $messageManager,
                         FlashBagManager $flashBagManager, $tabName = 'schoolyear')
    {
        if ($id === 'current')
        {
            $schoolYear = UserHelper::getCurrentSchoolYear();

            return $this->redirectToRoute('school_year_edit', ['id' => $schoolYear->getId(), 'tabName' => $tabName]);
        }

        $schoolYear = $id === 'Add' ? new SchoolYear() : $schoolYearManager->find($id);

        $form = $this->createForm(SchoolYearType::class, $schoolYear);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($form->get('specialDays')->getData() as $sd) {
                $schoolYear->addSpecialDay($sd);
                $em->persist($sd);
            }
            foreach($form->get('terms')->getData() as $term) {
                $schoolYear->addTerm($term);
                $em->persist($term);
            }
            $em->persist($schoolYear);
            $em->flush();

            $messageManager->add('success', 'school_year.success', [], 'SchoolYear');

            $flashBagManager->addMessages($messageManager);

            if ($id === 'Add')
                return new RedirectResponse($this->generateUrl('school_year_edit', ['id' => $schoolYear->getId(), 'tabName' => $tabName]));

            $em->refresh($schoolYear);

            $form = $this->createForm(SchoolYearType::class, $schoolYear);
        } else if ($id !== 'Add')
            $em->refresh($schoolYear);
        else if ($id === 'Add') {
            $schoolYear = $schoolYearManager->findLast($schoolYear);
            $form = $this->createForm(SchoolYearType::class, $schoolYear);
        }

        /*
            The calendar must be refreshed as the calendar will be written by the page loader from the cache
            and will error on the write if the database restraints are violated.  The form data is NOT
            impacted by this refresh.
        */

        return $this->render('SchoolYear/school_year.html.twig',
            [
                'form'     => $form->createView(),
                'fullForm' => $form,
                'calendar_id'  => $id,
                'manager' => $schoolYearManager,
            ]
        );
    }

    /**
     * deleteYear
     *
     * @param $id
     * @param SchoolYearManager $schoolYearManager
     * @param FlashBagManager $flashBagManager
     * @return RedirectResponse
     * @throws \Exception
     * @Route("/school/year/{id}/delete/", name="school_year_delete")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function deleteYear($id, SchoolYearManager $schoolYearManager, FlashBagManager $flashBagManager)
    {
        $schoolYear = $schoolYearManager->find($id);

        if ($schoolYearManager->canDelete($schoolYear)) {
            $em = $schoolYearManager->getEntityManager();
            $em->remove($schoolYear);
            $em->flush();
            $schoolYearManager->getMessageManager()->add('success', 'calendar.removal.success', ['%{name}' => $schoolYear->getName()]);
        } else
            $schoolYearManager->getMessageManager()->add('warning', 'calendar.removal.denied', ['%{name}' => $schoolYear->getName()]);

        $flashBagManager->addMessages($schoolYearManager->getMessageManager());

        return new RedirectResponse($this->generateUrl('calendar_years'));
    }

    /**
     * display
     *
     * @param $id
     * @param SchoolYearManager $schoolYearManager
     * @param bool $closeWindow
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/school/year/{id}/display/{closeWindow}", name="school_year_display")
     * @IsGranted("IS_FULLY_AUTHENTICATED")
     */
    public function display($id, SchoolYearManager $schoolYearManager, $closeWindow = false)
    {
        $repo = $schoolYearManager->getSchoolYearRepository();

        if ($id == 'current' || empty($id))
        {
            $schoolYear = SchoolYearManager::getCurrentSchoolYear();
        }
        else
            $schoolYear = $repo->find($id);

        $schoolYears = $repo->findBy([], ['name' => 'ASC']);

        $schoolYear = $repo->find($schoolYear->getId());

        /**
         * Set model classes for calendar. Arguments:
         * 1. For the whole calendar (watch $schoolYear variable). Default: \TFox\SchoolYearBundle\Service\WidgetService\SchoolYear
         * 2. Month. Default: \TFox\SchoolYearBundle\Service\WidgetService\Month
         * 3. Week. Default: '\TFox\SchoolYearBundle\Service\WidgetService\Week
         * 4. Day. Default: '\TFox\SchoolYearBundle\Service\WidgetService\Day'
         * To set default classes null should be passed as argument
         */

        $schoolYear->getTerms();

        $year = $schoolYearManager->generate($schoolYear); //Generate a calendar for specified year

        $schoolYearManager->setSchoolYearDays($year, $schoolYear);

        /*
         * Pass calendar to Twig
         */

        return $this->render('SchoolYear/displaySchoolYear.html.twig',
            array(
                'calendar'    => $schoolYear,
                'calendars'   => $schoolYears,
                'year'        => $year,
                'closeWindow' => $closeWindow,
            )
        );
    }

    /**
     * manageSpecialDay
     *
     * @param $id
     * @param SchoolYearManager $schoolYearManager
     * @param \Twig_Environment $twig
     * @param string $cid
     * @return JsonResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/school/year/{id}/special/day/{cid}/manage/", name="special_day_manage")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function manageSpecialDay($id, SchoolYearManager $schoolYearManager, \Twig_Environment $twig, $cid = 'ignore')
    {
        $schoolYearManager->find($id);

        $schoolYearManager->removeSpecialDay($cid);

        $form = $this->createForm(SchoolYearType::class, $schoolYearManager->getSchoolYear());

        $collection = $form->has('specialDays') ? $form->get('specialDays')->createView() : null;

        if (empty($collection)) {
            $schoolYearManager->getMessageManager()->add('warning', 'calendar.special_days.not_defined');
            $schoolYearManager->setStatus('warning');
        }

        $content = $this->renderView("SchoolYear/school_year_collection.html.twig",
            [
                'collection'    => $collection,
                'route'         => 'special_day_manage',
                'contentTarget' => 'specialDayCollection',
            ]
        );

        return new JsonResponse(
            [
                'content' => $content,
                'message' => $schoolYearManager->getMessageManager()->renderView($twig),
                'status'  => $schoolYearManager->getStatus(),
            ],
            200
        );
    }

    /**
     * manageTerm
     *
     * @param $id
     * @param SchoolYearManager $schoolYearManager
     * @param \Twig_Environment $twig
     * @param string $cid
     * @return JsonResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @Route("/school/year/{id}/term/{cid}/manage/", name="term_manage")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function manageTerm($id, SchoolYearManager $schoolYearManager, \Twig_Environment $twig, $cid = 'ignore')
    {
        $schoolYearManager->find($id);

        $schoolYearManager->removeTerm($cid);

        $form = $this->createForm(SchoolYearType::class, $schoolYearManager->getSchoolYear());

        $collection = $form->has('terms') ? $form->get('terms')->createView() : null;

        if (empty($collection)) {
            $schoolYearManager->getMessageManager()->add('warning', 'calendar.terms.not_defined');
            $schoolYearManager->setStatus('warning');
        }

        $content = $this->renderView("SchoolYear/school_year_collection.html.twig",
            [
                'collection'    => $collection,
                'route'         => 'calendar_term_manage',
                'contentTarget' => 'termCollection',
            ]
        );

        return new JsonResponse(
            [
                'content' => $content,
                'message' => $schoolYearManager->getMessageManager()->renderView($twig),
                'status'  => $schoolYearManager->getStatus(),
            ],
            200
        );
    }

    /**
     * delete
     *
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param FlashBagManager $flashBagManager
     * @return RedirectResponse
     * @Route("/school/year/term/{id}/delete/", name="term_delete")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function delete($id, EntityManagerInterface $entityManager, FlashBagManager $flashBagManager)
    {
        $term = $entityManager->getRepository(SchoolYearTerm::class)->find($id);

        $flashBagManager->setDomain('SchoolYear');

        $schoolYear = $term->getSchoolYear();

        if ($term->canDelete())
        {
            $entityManager->remove($term);
            $entityManager->flush();
            $flashBagManager->add(
                'success', 'year.term.delete.success',
                [
                    '%name%' => $term->getName(),
                ]
            );
        }
        else
        {
            $flashBagManager->add(
                'warning', 'year.term.delete.warning',
                [
                    '%name%' => $term->getName(),
                ]
            );
        }

        $flashBagManager->addMessages();

        return $this->redirectToRoute('school_year_edit', ['id' => $schoolYear->getId(), '_fragment' => 'terms']);
    }

    /**
     * deleteAction
     *
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @param FlashBagManager $flashBagManager
     * @param SettingManager $settingManager
     * @return RedirectResponse
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/school/year/special/day/{id}/delete/", name="special_day_delete")
     * @Security("is_granted('USE_ROUTE', ['school_year_manage', {}])")
     */
    public function deleteAction($id, EntityManagerInterface $entityManager, FlashBagManager $flashBagManager, SettingManager $settingManager)
    {
        $sday = $entityManager->getRepository(SchoolYearSpecialDay::class)->find($id);

        $schoolYear = $sday->getSchoolYear();
        $flashBagManager->setDomain('Calendar');

        if ($sday->canDelete())
        {
            $em = $this->get('doctrine')->getManager();
            $em->remove($sday);
            $em->flush();
            $flashBagManager->add(
                'success', 'calendar.special_day.delete.success',
                [
                    '%{name}' => $sday->getDay()->format($settingManager->get('date.format.short')),
                ]
            );
        }
        else
        {
            $flashBagManager->add(
                'warning', 'calendar.special_day.delete.warning',
                [
                    '%name%' => $sday->getDay()->format($settingManager->get('date.format.short')),
                ]
            );
        }

        $flashBagManager->addMessages();

        return $this->redirectToRoute('school_year_edit', ['id' => $schoolYear->getId(), '_fragment' => 'specialDays']);
    }
}