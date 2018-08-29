<?php
namespace App\Validator\Constraints;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class PersonEmailValidator
 * @package App\Validator\Constraints
 */
class PersonEmailValidator extends ConstraintValidator
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * PersonEmailValidator constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @param mixed      $value
	 * @param Constraint $constraint
	 */
	public function validate($value, Constraint $constraint)
	{
        if (empty($value))
			return;

		$object = $this->context->getObject();

		if ($constraint->errorPath == 'email' || $constraint->errorPath == 'emailAlternate')
		{

			$result = $this->em->getRepository(Person::class)->createQueryBuilder('p')
				->select('p.id')
				->where('(p.email = :email1 OR p.emailAlternate = :email2) AND p.id != :id')
				->setParameter('email1', $value)
				->setParameter('email2', $value)
				->setParameter('id', $object->getId())
				->getQuery()
				->getResult();

			if (!empty($result))
			{
				$this->context->buildViolation($constraint->message)
					->setParameter('%string%', $value)
                    ->setTranslationDomain('Person')
					->addViolation();
			}
		}
	}
}