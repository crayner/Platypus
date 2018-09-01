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
 * Date: 1/09/2018
 * Time: 10:13
 */
namespace App\Validator\Constraints;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AddressValidator
 * @package App\Validator\Constraints
 */
class AddressValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value->getStreetName()))
            $this->context->buildViolation('address.street_name.empty')
                ->setTranslationDomain('Address')
                ->addViolation();
        if (empty($value->getLocality()))
            $this->context->buildViolation('address.locality.empty')
                ->setTranslationDomain('Address')
                ->addViolation();

        $rule = $constraint->rule;

        if (empty($rule))
        {
            return ;
        }

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'streetName' => [],
            'streetNumber' => [],
            'propertyName' => [],
            'buildingType' => [],
            'buildingNumber' => [],
            'locality' => [],
            'postCode' => [],
        ]);

        $rule = $resolver->resolve($rule);

        foreach($rule as $name=>$details){
            $resolver = new OptionsResolver();
            $resolver->setDefaults([
                'required' => false,
                'regex' => false,
            ]);
            $details = $resolver->resolve($details);

            $method = 'get' . ucfirst($name);

            if ($details['required'] && empty($value->$method()))
                $this->context->buildViolation('address.'.$name.'.empty')
                    ->setTranslationDomain('Address')
                    ->addViolation();

            if (!empty($value->$method()) && $details['regex'] && preg_match($details['regex'], $value->$method()) !== 1)
                $this->context->buildViolation('address.'.$name.'.not_valid')
                    ->setTranslationDomain('Address')
                    ->addViolation();

        }

        return ;
    }
}