<?php
namespace App\Validator;

use App\Validator\Constraints\SchoolYearDateValidator;
use Symfony\Component\Validator\Constraint;

class SchoolYearDate extends Constraint
{
	public $message = 'calendar.validation.not_one_year';

	public $fields;

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return SchoolYearDateValidator::class;
	}

	public function getRequiredOptions()
    {
        return [
            'fields',
        ];
    }
}
