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
 * Time: 17:00
 */
namespace App\Validator;

use App\Validator\Constraints\OrgNameValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class OrgName
 * @package App\Validator
 */
class OrgName extends Constraint
{
    public $message = 'org.name.not.valid';

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return OrgNameValidator::class;
    }
}