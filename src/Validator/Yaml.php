<?php
namespace App\Validator;

use App\Validator\Constraints\YamlValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Yaml extends Constraint
{
	public $message = 'yaml.validation.error';

	public $transDomain = 'Setting';

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return YamlValidator::class;
	}
}