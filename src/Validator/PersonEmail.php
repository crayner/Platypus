<?php
namespace App\Validator;

use App\Validator\Constraints\PersonEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class PersonEmail
 * @package App\Validator
 */
class PersonEmail extends Constraint
{
    /**
     * @var string
     */
	public $message = 'person.validator.email.unique';

    /**
     * @var string
     */
	public $errorPath;

    /**
     * validatedBy
     *
     * @return string
     */
	public function validatedBy()
	{
		return PersonEmailValidator::class;
	}

    /**
     * getRequiredOptions
     *
     * @return array
     */
	public function getRequiredOptions()
    {
        return [
            'errorPath',
        ];
    }
}
