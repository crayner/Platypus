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
 * Date: 13/08/2018
 * Time: 11:04
 */
namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class OnlyOneCanBeValidator
 * @package App\Validator\Constraints
 */
class OnlyOneCanBeValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $method = 'get' . ucfirst($constraint->fieldName);
        $entityName = get_class($value);

        $field_ok = false;

        if (method_exists($entityName, $method))
            $field_ok = true;

        if (! $field_ok )
            $method = 'is' . ucfirst($constraint->fieldName);

        if (method_exists($entityName, $method))
            $field_ok = true;

        $path = $constraint->atPath ?: $constraint->fieldName;

        if (! $field_ok)
        {
            $this->context->buildViolation('only_one_can_be.invalid.field')
                ->setParameter('%{field}', $constraint->fieldName)
                ->setParameter('%{entity}', $entityName)
                ->setTranslationDomain($constraint->translationDomain)
                ->atPath($path)
                ->addViolation()
            ;
            return;
        }

        $entity = $this->getEntityManager()->getRepository($entityName)->findOneBy([$constraint->fieldName => $constraint->fieldContent]);

        if ($entity instanceof $entityName && $entity->getId() !== $value->getId() && $value->$method() === $constraint->fieldContent )
        {
            $this->context->buildViolation('only_one_can_be.row.exists')
                ->setParameter('%{field}', $constraint->fieldName)
                ->setParameter('%{entity}', $entityName)
                ->setParameter('%{content}', $constraint->fieldContent)
                ->atPath($path)
                ->setTranslationDomain($constraint->translationDomain)
                ->addViolation()
            ;
            return;
        }


        if ($constraint->mustBeOne && $value->$method() !== $constraint->fieldContent && $entity->getId() === $value->getId() )
        {
            $this->context->buildViolation('only_one_can_be.row.missing')
                ->setParameter('%{field}', $constraint->fieldName)
                ->setParameter('%{entity}', $entityName)
                ->setParameter('%{content}', $constraint->fieldContent)
                ->atPath($path)
                ->setTranslationDomain($constraint->translationDomain)
                ->addViolation()
            ;
            return;
        }

    }

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * OnlyOneCanBeValidator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}