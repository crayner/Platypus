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
 * Date: 25/09/2018
 * Time: 15:05
 */
namespace App\Validator;

use App\Validator\Constraints\TimetableValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class Timetable
 * @package App\Validator
 */
class Timetable extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The timetable is not valid. Selected year groups [%{yearGroups}] are already active in the %{schoolYear} school year!';

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return TimetableValidator::class;
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
