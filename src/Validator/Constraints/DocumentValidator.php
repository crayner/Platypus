<?php
namespace App\Validator\Constraints;

use App\Validator\Document;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator;
use Symfony\Component\Validator\Constraints\ImageValidator;

/**
 * Class DocumentValidator
 * @package App\Validator\Constraints
 */
class DocumentValidator extends ImageValidator
{
    /**
     * validate
     *
     * @param $value
     * @param Constraint $constraint
     */
	public function validate($value, Constraint $constraint)
	{
        if (!$constraint instanceof Document) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Document');
        }

        $violations = \count($this->context->getViolations());

        $fileVal = new FileValidator();

        $fileVal->validate($value, $constraint);

        $failed = \count($this->context->getViolations()) !== $violations;

        if ($failed || null === $value || '' === $value) {
            return;
        }
        if (! $value instanceof File) {
            $xxx = new File($value);
        } else
            $xxx = $value;

        if ($xxx->getMimeType() === 'application/pdf')
            return;

		parent::validate($value, $constraint);
	}
}