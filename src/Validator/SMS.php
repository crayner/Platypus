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
namespace App\Validator;

use App\Validator\Constraints\SMSValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class SMS
 * @package App\Validator
 */
class SMS extends Constraint
{
    public $message = 'paypal.validation.fail';

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return SMSValidator::class;
    }
}