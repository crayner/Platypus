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
 * Time: 15:19
 */
namespace App\Validator\Constraints;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DaysOfWeekValidator
 * @package App\Validator\Constraints
 */
class DaysOfWeekValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value->getDays() instanceof ArrayCollection) {
            $this->context->buildViolation($constraint->message . 'array')
                ->setTranslationDomain('School')
                ->addViolation();
        }

        if ($value->getDays()->count() !== 7) {
            $this->context->buildViolation($constraint->message . 'count')
                ->setTranslationDomain('School')
                ->setParameter('%count%', $value->getDays()->count())
                ->addViolation();
            return;
        }
    }
}