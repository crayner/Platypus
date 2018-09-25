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
 * Date: 25/09/2018
 * Time: 15:06
 */
namespace App\Validator\Constraints;

use App\Repository\TimetableRepository;
use App\Util\SchoolYearHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class TimetableValidator
 * @package App\Validator\Constraints
 */
class TimetableValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value) || !$value->isActive())
            return ;

        $others = $this->repository->createQueryBuilder('t')
            ->where('t.active = :active')
            ->andWhere('t.schoolYear = :schoolYear')
            ->andWhere('t.id != :timetable')
            ->setParameter('active', true)
            ->setParameter('schoolYear', SchoolYearHelper::getCurrentSchoolYear())
            ->setParameter('timetable', $value->getId())
            ->getQuery()
            ->getResult();

        $duplicates = [];
        foreach($others as $w)
            foreach($w->getYearGroups() as $yg)
                foreach ($value->getYearGroups() as $pyg)
                    if ($pyg->getId() === $yg->getId())
                        $duplicates[$yg->getId()] = $yg->getName();
        if (! empty($duplicates)) {
            $duplicates = implode(',', $duplicates);

            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Timetable')
                ->setParameter('%{yearGroups}',  $duplicates)
                ->setParameter('%{schoolYear}', SchoolYearHelper::getCurrentSchoolYear()->getName())
                ->addViolation();
        }
    }

    /**
     * @var TimetableRepository
     */
    private $repository;

    /**
     * Timetable constructor.
     * @param TimetableRepository $repository
     */
    public function __construct(TimetableRepository $repository)
    {
        $this->repository = $repository;
    }
}
