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
 * Date: 31/08/2018
 * Time: 14:06
 */
namespace App\Validator\Constraints;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class LocalityValidator
 * @package App\Validator\Constraints
 */
class LocalityValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value->getName()))
            $this->context->buildViolation('locality.name.empty')
                ->setTranslationDomain('Address')
                ->addViolation();
        if (empty($value->getCountry()))
            $this->context->buildViolation('locality.country.empty')
                ->setTranslationDomain('Address')
                ->addViolation();

        $rule = $constraint->rule;

        if (empty($rule))
        {
            return;
        }

        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'postCode',
            'territory',
        ]);

        $resolver->resolve($rule);

        foreach($rule as $name=>$details){
            $resolver = new OptionsResolver();
            $resolver->setDefaults([
                'required' => false,
                'regex' => false,
            ]);
            $resolver->resolve($details);

            $method = 'get' . ucfirst($name);

            if ($details['required'] && empty($value->$method()))
                $this->context->buildViolation('locality.'.$name.'.empty')
                    ->setTranslationDomain('Address')
                    ->addViolation();

            if (!empty($value->$method()) && $details['regex'] && preg_match($details['regex'], $value->$method()) !== 1)
                $this->context->buildViolation('locality.'.$name.'.not_valid')
                    ->setTranslationDomain('Address')
                    ->addViolation();

        }
    }
}