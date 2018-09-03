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
 * Time: 10:12
 */
namespace App\Validator;

use App\Validator\Constraints\AddressValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class Address
 * @package App\Validator
 */
class Address extends Constraint
{
    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return AddressValidator::class;
    }

    /**
     * getTargets
     *
     * @return array|string
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}