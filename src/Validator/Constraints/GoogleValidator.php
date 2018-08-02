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
 * Date: 1/08/2018
 * Time: 13:45
 */
namespace App\Validator\Constraints;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GoogleValidator
 * @package App\Validator\Constraints
 */
class GoogleValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $value = Yaml::parse($value);

        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'o_auth',
            'client_id',
            'client_secret',
        ]);

        try {
            $resolver->resolve($value);
        } catch (UndefinedOptionsException $e) {
            $this->context->buildViolation($e->getMessage())
                ->addViolation();
        } catch (MissingOptionsException $e) {
            $this->context->buildViolation($e->getMessage())
                ->addViolation();
            return ;
        }

        $options = ['0', '1'];
        if (! in_array($value['o_auth'], $options))
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', 'o_auth')
                ->setParameter('{options}', implode(',', $options))
                ->addViolation();

        if ($value['o_auth'] === '1' && (empty($value['client_id']) || empty($value['client_secret'])))
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', '[client_id, client_secret]')
                ->setParameter('{options}', 'NOT EMPTY')
                ->addViolation();

    }
}