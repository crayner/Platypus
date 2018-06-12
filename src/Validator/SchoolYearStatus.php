<?php
namespace App\Validator;

use App\Validator\Constraints\SchoolYearStatusValidator;
use Symfony\Component\Validator\Constraint;

class SchoolYearStatus extends Constraint
{
	public $message = 'calendar.error.status';

	public $id;

	public function validatedBy()
	{
		return SchoolYearStatusValidator::class;
	}
}
