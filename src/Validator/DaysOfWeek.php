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
 * Date: 14/06/2018
 * Time: 15:17
 */
namespace App\Validator;

use App\Validator\Constraints\DaysOfWeekValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class DaysOfWeek
 * @package App\Validator
 */
class DaysOfWeek extends Constraint
{
    /**
     * @var string
     */
    public $message = 'days_of_week.validation.';

    /**
     * getTargets
     *
     * @return array|string
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return DaysOfWeekValidator::class;
    }
}