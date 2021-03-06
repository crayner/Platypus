<?php
namespace App\Validator;

use App\Validator\Constraints\TwigValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Twig extends Constraint
{
	public $message = 'twig.error';

	public $transDomain = 'System';

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return TwigValidator::class;
	}

}