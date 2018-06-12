<?php
namespace App\Validator;

use App\Entity\SchoolYear;
use App\Validator\Constraints\SpecialDayDateValidator;
use Symfony\Component\Validator\Constraint;

class SpecialDayDate extends Constraint
{
    /**
     * @var string
     */
	public $message = 'specialday.error.date';

    /**
     * @var SchoolYear
     */
	public $schoolYear;

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
	{
		return SpecialDayDateValidator::class;
	}

    /**
     * getRequiredOptions
     *
     * @return array
     */
    public function getRequiredOptions()
    {
        return [
            'schoolYear',
        ];
    }
}
