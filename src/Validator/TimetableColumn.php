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
 * Date: 26/09/2018
 * Time: 16:00
 */
namespace App\Validator;

use App\Validator\Constraints\TimetableColumnValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class TimetableColumn
 * @package App\Validator
 */
class TimetableColumn extends Constraint
{
    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return TimetableColumnValidator::class;
    }

    /**
     * getTargets
     *
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}