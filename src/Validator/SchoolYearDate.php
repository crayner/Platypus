<?php
namespace App\Validator;

use App\Validator\Constraints\SchoolYearDateValidator;
use Symfony\Component\Validator\Constraint;

class SchoolYearDate extends Constraint
{
	public $message = 'school_year.validation.not_one_year';

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return SchoolYearDateValidator::class;
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
