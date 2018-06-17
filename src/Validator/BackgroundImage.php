<?php
namespace App\Validator;

use App\Validator\Constraints\BackgroundImageValidator;
use Symfony\Component\Validator\Constraints\Image;

/**
 * Class BackgroundImage
 * @package App\Validator
 */
class BackgroundImage extends Image
{
	public $minWidth = 1200;
	public $maxSize = '1024k';
	public $allowSquare = false;
	public $allowLandscape = true;
	public $allowPortrait = false;
	public $detectCorrupted = true;

	/**
	 * @return string
	 */
	public function validatedBy()
	{
		return BackgroundImageValidator::class;
	}
}
