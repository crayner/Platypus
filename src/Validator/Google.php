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
 * Time: 13:44
 */
namespace App\Validator;

use App\Validator\Constraints\GoogleValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class Google
 * @package App\Validator
 */
class Google extends Constraint
{
    public $message = 'google.validation.fail';

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return GoogleValidator::class;
    }
}