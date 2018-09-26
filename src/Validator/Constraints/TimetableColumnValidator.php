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
 * Date: 26/09/2018
 * Time: 16:03
 */
namespace App\Validator\Constraints;

use App\Entity\TimetableColumnRow;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class TimetableColumnValidator
 * @package App\Validator
 */
class TimetableColumnValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) return ;

        $rows = $value->getTimetableColumnRows();

        $iterator = $rows->getIterator();
        $iterator->uasort(function ($a, $b) {
            return ($a->getTimeStart() < $b->getTimeStart()) ? -1 : 1;
        });

        $rows = new ArrayCollection(iterator_to_array($iterator, false));

        $start = $value->getDayOfWeek()->getSchoolStart();
        $end = $value->getDayOfWeek()->getSchoolEnd();


        $row = $rows->first();
        $previous = null;
        do {
            if ($previous instanceof TimetableColumnRow)
            {
                if ($previous->getTimeEnd()->format('Hi') > $row->getTimeStart()->format('Hi'))
                {
                    $this->context->buildViolation("An overlap exists between %previous% and %current% rows.")
                        ->setParameter('%previous%', $previous->getName().' '.$previous->getTimeEnd()->format('H:i'))
                        ->setParameter('%current%', $row->getName().' '.$row->getTimeStart()->format('H:i'))
                        ->setTranslationDomain('Timetable')
                        ->addViolation();
                }
                if ($row->getTimeStart()->format('Hi') > $previous->getTimeEnd()->format('Hi')) {
                    $this->context->buildViolation('A gap in time exists between %previous% and %current%.')
                        ->setParameter('%previous%', $previous->getName().' '.$previous->getTimeEnd()->format('H:i'))
                        ->setParameter('%current%', $row->getName().' '.$row->getTimeStart()->format('H:i'))
                        ->setTranslationDomain('Timetable')
                        ->addViolation();
                }
            }
            $previous = $rows->current();
        } while (false !== ($row = $rows->next()));

        if ($start < $rows->first()->getTimeStart()) {
            $this->context->buildViolation('Their is a gap between the start time %start% and the first period %first%. <a href="%route%" target="_blank">Days of the week can be altered here. </a>')
                ->setParameter('%start%', $start->format('H:i'))
                ->setParameter('%first%', $rows->first()->getName())
                ->setParameter('%route%', $this->router->generate('days_of_week_manage'))
                ->setTranslationDomain('Timetable')
                ->addViolation();
        }
        if ($end > $rows->last()->getTimeEnd()) {
            $this->context->buildViolation('Their is a gap between the end time %end% and the last period %limit%. <a href="%route%" target="_blank">Days of the week can be altered here. </a>')
                ->setParameter('%limit%', $rows->last()->getName())
                ->setParameter('%end%', $end->format('H:i'))
                ->setParameter('%route%', $this->router->generate('days_of_week_manage'))
                ->setTranslationDomain('Timetable')
                ->addViolation();
        }
        if ($start > $rows->first()->getTimeStart()) {
            $this->context->buildViolation('The rows starts at %start% before the school starts teaching at %opens%. <a href="%route%" target="_blank">Days of the week can be altered here. </a>')
                ->setParameter('%start%', $rows->first()->getTimeStart()->format('H:i'))
                ->setParameter('%opens%', $start->format('H:i'))
                ->setParameter('%route%', $this->router->generate('days_of_week_manage'))
                ->setTranslationDomain('Timetable')
                ->addViolation();
        }

        if ($end < $rows->last()->getTimeEnd()) {
            $this->context->buildViolation('The rows ends at %end% after the school finished teaching at %closes%. <a href="%route%" target="_blank">Days of the week can be altered here. </a>')
                ->setParameter('%end%', $rows->last()->getTimeEnd()->format('H:i'))
                ->setParameter('%closes%', $end->format('H:i'))
                ->setParameter('%route%', $this->router->generate('days_of_week_manage'))
                ->setTranslationDomain('Timetable')
                ->addViolation();
        }

        return $value;

    }

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * TimetableColumnValidator constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
}