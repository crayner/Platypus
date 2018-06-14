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
 * Time: 15:34
 */
namespace App\Validator\Constraints;

use App\Entity\DayOfWeek;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DayOfWeekValidator
 * @package App\Validator\Constraints
 */
class DayOfWeekValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof DayOfWeek){
            $this->context->buildViolation($constraint->message . 'object')
                ->setTranslationDomain('School')
                ->atPath('schoolOpen')
                ->addViolation();
        } else {
            if ($value->isSchoolDay()) {
                //test Times
                if ($value->getSchoolOpen() >= $value->getSchoolStart())
                {
                    $this->context->buildViolation($constraint->message . 'start')
                        ->setTranslationDomain('School')
                        ->atPath('schoolStart')
                        ->addViolation();
                }
                if ($value->getSchoolStart() >= $value->getSchoolEnd())
                {
                    $this->context->buildViolation($constraint->message . 'end')
                        ->setTranslationDomain('School')
                        ->atPath('schoolEnd')
                        ->addViolation();
                }
                if ($value->getSchoolEnd() >= $value->getSchoolClose())
                {
                    $this->context->buildViolation($constraint->message . 'close')
                        ->setTranslationDomain('School')
                        ->atPath('schoolClose')
                        ->addViolation();
                }
            } else {
                $value->setSchoolClose(null)
                    ->setSchoolEnd(null)
                    ->setSchoolOpen(null)
                    ->setSchoolStart(null);
                return $value;
            }
        }
    }
}