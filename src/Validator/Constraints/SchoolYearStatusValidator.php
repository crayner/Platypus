<?php
namespace App\Validator\Constraints;

use App\Repository\SchoolYearRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator ;

class SchoolYearStatusValidator extends ConstraintValidator
{
    /**
     * @var SchoolYearRepository
     */
	private $schoolYearRepository;

    /**
     * CalendarStatusValidator constructor.
     * @param SchoolYearRepository $schoolYearRepository
     */
    public function __construct(SchoolYearRepository $schoolYearRepository)
	{
		$this->schoolYearRepository = $schoolYearRepository;
	}

	/**
	 * @param mixed      $value
	 * @param Constraint $constraint
	 *
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function validate($value, Constraint $constraint)
	{
		if (empty($value))
			return;


		if (empty($constraint->id))
		{
			throw new \InvalidArgumentException('ID is not set for Calendar Status validation.');
		}

		if ($value == 'current')
		{
			$xx = $this->schoolYearRepository->createQueryBuilder('c')
				->where('c.status = :status')
				->andWhere('c.id != :calendar_id')
				->setParameter('status', 'current')
				->setParameter('calendar_id', $constraint->id)
				->getQuery()
				->getOneOrNullResult();
			if (!is_null($xx) && $xx->getId() !== $constraint->id)
				$this->context->buildViolation($constraint->message)
					->addViolation();
		}

	}
}