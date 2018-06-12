<?php
namespace App\Validator\Constraints;

use App\Repository\SchoolYearRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SchoolYearDateValidator extends ConstraintValidator
{
    /**
     * @var SchoolYearRepository 
     */
	private $schoolYearRepository;

    /**
     * SchoolYearDateValidator constructor.
     * @param SchoolYearRepository $schoolYearRepository
     */
    public function __construct(SchoolYearRepository $schoolYearRepository)
	{
		$this->schoolYearRepository = $schoolYearRepository;
	}

	public function validate($value, Constraint $constraint)
	{
		if (empty($value))
			return;

		$schoolYear  = $constraint->fields;
		$start = $schoolYear->getFirstDay();
		$end   = $schoolYear->getLastDay();
		$name  = $schoolYear->getName();

		if (!$start instanceof \DateTime || !$end instanceof \DateTime)
		{
			$this->context->buildViolation('calendar.validation.invalid.format')
                ->setTranslationDomain('Calendar')
				->addViolation();
			return;
		}
		if ($start > $end)
		{
			$this->context->buildViolation('calendar.validation.invalid.order')
                ->setTranslationDomain('Calendar')
				->addViolation();
			return;
		}
		if ($start->diff($end)->y > 0)
		{
			$this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Calendar')
				->addViolation();
			return;
		}

		$schoolYears = $this->schoolYearRepository->createQueryBuilder('y')
			->where('y.id != :id')
			->setParameter('id', $schoolYear->getId())
			->getQuery()
			->getResult();

		if (is_array($schoolYears))
			foreach ($schoolYears as $schoolYear)
			{
				if ($schoolYear->getFirstDay() >= $start && $schoolYear->getFirstDay() <= $end)
				{
					$this->context->buildViolation('calendar.validation.overlapped', array('%name1%' => $schoolYear->getName(), '%name2%' => $name))
                        ->setTranslationDomain('Calendar')
						->addViolation();
					return;
				}
				if ($schoolYear->getLastDay() >= $start && $schoolYear->getLastDay() <= $end)
				{
					$this->context->buildViolation('calendar.validation.overlapped', array('%name1%' => $schoolYear->getName(), '%name2%' => $name))
                        ->setTranslationDomain('Calendar')
						->addViolation();
					return;
				}
			}
	}
}