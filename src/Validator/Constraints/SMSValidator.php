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
 * Time: 16:13
 */
namespace App\Validator\Constraints;

use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SMSValidator
 * @package App\Validator\Constraints
 */
class SMSValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $value = Yaml::parse($value);

        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'enable',
            'username',
            'password',
            'url',
            'urlCredit',
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
        if (! in_array($value['enable'], $options))
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', 'enable')
                ->setParameter('{options}', implode(',', $options))
                ->addViolation();

        if ($value['enable'] === '1' && (empty($value['username']) || empty($value['password']) || empty($value['url']) || empty($value['urlCredit'])))
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', '[username, password, url, urlCredit]')
                ->setParameter('{options}', 'NOT EMPTY')
                ->addViolation();

        if ($value['enable'] === '1' && ! filter_var($value['url'], FILTER_VALIDATE_URL))
        {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', 'url')
                ->setParameter('{options}', 'URL')
                ->addViolation();
        }
        if ($value['enable'] === '1' && ! filter_var($value['urlCredit'], FILTER_VALIDATE_URL))
        {
            $this->context->buildViolation($constraint->message)
                ->setTranslationDomain('Setting')
                ->setParameter('{field}', 'urlCredit')
                ->setParameter('{options}', 'URL')
                ->addViolation();
        }
    }
}