<?php
namespace App\Validator;

use App\Validator\Constraints\TermDateValidator;
use Symfony\Component\Validator\Constraint;

class TermDate extends Constraint
{
	public $message = 'year.term.error.date';

	public $schoolYear;

	public function validatedBy()
	{
		return TermDateValidator::class;
	}

	public function getRequiredOptions()
    {
        return [
            'schoolYear'
        ];
    }
}
