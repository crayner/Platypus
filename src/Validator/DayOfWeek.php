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
 * Time: 15:32
 */
namespace App\Validator;

use App\Validator\Constraints\DayOfWeekValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class DayOfWeek
 * @package App\Validator
 */
class DayOfWeek extends Constraint
{
    /**
     * @var string
     */
    public $message = 'day_of_week.validation.';

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
        return DayOfWeekValidator::class;
    }
}