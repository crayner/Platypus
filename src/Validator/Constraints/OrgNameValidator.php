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
 * Date: 31/07/2018
 * Time: 17:02
 */
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class OrgNameValidator
 * @package App\Validator\Constraints
 */
class OrgNameValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $value = Yaml::parse($value);
        if (!is_array($value)) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->addViolation();
            return;
        }
        if (count($value) !== 2) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->addViolation();
            return;
        }
        if (empty($value['long'] || empty($value['short']))) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->addViolation();
            return;
        }
        if (strlen($value['short']) > 6) {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->addViolation();
            return;
        }
    }
}