<?php
namespace App\Validator;

use App\Validator\Constraints\GoogleOAuthValidator;
use Symfony\Component\Validator\Constraint;

class GoogleOAuth extends Constraint
{
	public $message = 'google.oauth.error.message';

	public $transDomain = 'Install';

	public function validatedBy()
	{
		return GoogleOAuthValidator::class;
	}

}