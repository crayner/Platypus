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
 * Date: 27/06/2018
 * Time: 13:34
 */
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ScaleGradesValidator
 * @package App\Validator\Constraints
 */
class ScaleGradesValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $count = 0;
        foreach($value->getIterator() as $entity) {
            if ($entity->isDefault())
                $count++;
            if ($count > 1) {
                $this->context->buildViolation('scale_grade.default.unique.error')
                    ->setTranslationDomain('School')
                    ->setParameter('%{name}', $entity->getValue())
                    ->addViolation();
                break;
            }
        }
    }
}