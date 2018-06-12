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

use App\Manager\FlashBagManager;
use App\Manager\MessageManager;
use App\Manager\SchoolYearManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SchoolYearController extends Controller
{

    /**
     * schoolYearManage
     * @Route("/school/year/manage/", name="school_year_manage")
     */
    public function schoolYearManage(SchoolYearManager $schoolYearManager)
    {
        $schoolYears = $schoolYearManager->getSchoolYearRepository()->findBy([], ['firstDay' => 'DESC'], 12);

        return $this->render('SchoolYear/manage.html.twig',
            [
                'school_years' => $schoolYears,
                'manager' => $schoolYearManager,
            ]
        );

    }

    /**
     * @Route("/school/year/{id}/edit/", name="school_year_edit")
     * @IsGranted("ROLE_REGISTRAR")
     * @param $id
     * @param Request $request
     * @param SchoolYearManager $schoolYearManager
     * @param EntityManagerInterface $em
     * @param MessageManager $messageManager
     * @param FlashBagManager $flashBagManager
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request,
                         SchoolYearManager $schoolYearManager,
                         EntityManagerInterface $em, MessageManager $messageManager,
                         FlashBagManager $flashBagManager)
    {
        if ($id === 'current')
        {
            $calendar = $schoolYearManager->getCurrentCalendar();

            return $this->redirectToRoute('calendar_edit', ['id' => $calendar->getId()]);
        }

        $calendar = $id === 'Add' ? new Calendar() : $schoolYearManager->getCalendarRepository()->find($id);

        $form = $this->createForm(CalendarType::class, $calendar, [ 'calendarGradeManager' => $calendarGradeManager]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->persist($calendar);
            $em->flush();

            $messageManager->add('success', 'calendar.success', [], 'Calendar');

            $flashBagManager->addMessages($messageManager);

            if ($id === 'Add')
                return new RedirectResponse($this->generateUrl('calendar_edit', array('id' => $calendar->getId())));

            $em->refresh($calendar);

            $form = $this->createForm(CalendarType::class, $calendar, ['calendarGradeManager' => $calendarGradeManager]);
        } else
            $em->refresh($calendar);

        /*
            The calendar must be refreshed as the calendar will be written by the page loader from the cache
            and will error on the write if the database restraints are violated.  The form data is NOT
            impacted by this refresh.
        */

        return $this->render('Calendar/calendar.html.twig',
            [
                'form'     => $form->createView(),
                'fullForm' => $form,
                'calendar_id'  => $id,
                'manager' => $schoolYearManager,
            ]
        );
    }
    
    /**
     * @param $id
     * @param SchoolYearManager $schoolYearManager
     * @return RedirectResponse
     * @Route("/school/year/{id}/delete/", name="school_year_delete")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function deleteYear($id, SchoolYearManager $schoolYearManager, FlashBagManager $flashBagManager)
    {
        $this->denyAccessUnlessGranted('ROLE_REGISTRAR', null, null);

        $calendar = $schoolYearManager->find($id);

        if ($schoolYearManager->canDelete($calendar)) {
            $em = $schoolYearManager->getEntityManager();
            $em->remove($calendar);
            $em->flush();
            $schoolYearManager->getMessageManager()->add('success', 'calendar.removal.success', ['%{name}' => $calendar->getName()]);
        } else
            $schoolYearManager->getMessageManager()->add('warning', 'calendar.removal.denied', ['%{name}' => $calendar->getName()]);

        $flashBagManager->addMessages($schoolYearManager->getMessageManager());

        return new RedirectResponse($this->generateUrl('calendar_years'));
    }

    /**
     * @param   int $id
     * @param   bool $closeWindow
     * @param SchoolYearManager $schoolYearManager
     * @return  Response
     * @Route("/school/year/{id}/display/{closeWindow}", name="school_year_display")
     * @IsGranted("ROLE_USER")
     */
    public function display($id, $closeWindow = false, SchoolYearManager $schoolYearManager)
    {
        $repo = $schoolYearManager->getSchoolYearRepository();

        if ($id == 'current' || empty($id))
        {
            $calendar = SchoolYearManager::getCurrentSchoolYear();
        }
        else
            $calendar = $repo->find($id);

        $calendars = $repo->findBy([], ['name' => 'ASC']);

        $calendar = $repo->find($calendar->getId());

        /**
         * Set model classes for calendar. Arguments:
         * 1. For the whole calendar (watch $calendar variable). Default: \TFox\CalendarBundle\Service\WidgetService\Calendar
         * 2. Month. Default: \TFox\CalendarBundle\Service\WidgetService\Month
         * 3. Week. Default: '\TFox\CalendarBundle\Service\WidgetService\Week
         * 4. Day. Default: '\TFox\CalendarBundle\Service\WidgetService\Day'
         * To set default classes null should be passed as argument
         */

        $calendar->getTerms();

        $year = $schoolYearManager->generate($calendar); //Generate a calendar for specified year

        $schoolYearManager->setCalendarDays($year, $calendar);

        /*
         * Pass calendar to Twig
         */

        return $this->render('Calendar/displayCalendar.html.twig',
            array(
                'calendar'    => $calendar,
                'calendars'   => $calendars,
                'year'        => $year,
                'closeWindow' => $closeWindow,
            )
        );
    }
}