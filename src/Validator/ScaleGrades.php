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
 * Date: 27/06/2018
 * Time: 13:34
 */
namespace App\Validator;

use App\Validator\Constraints\ScaleGradesValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class ScaleGrades
 * @package App\Validator
 */
class ScaleGrades extends Constraint
{
    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return ScaleGradesValidator::class;
    }
}