<?php
namespace App\Validator;

use App\Validator\Constraints\DocumentValidator;
use Symfony\Component\Validator\Constraints\Image;

class Document extends Image
{
	public $mimeTypes = ['image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'application/pdf'];
	public $mimeTypesMessage = 'The file is not a valid image or PDF.';
	public $minWidth = 250;
	public $maxSize = '750k';
	public $allowSquare = true;
	public $allowLandscape = false;
	public $allowPortrait = true;
	public $detectCorrupted = true;
	public $minRatio = 0.707;
	public $maxRatio = 1;

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
	{
		return DocumentValidator::class;
	}
}
