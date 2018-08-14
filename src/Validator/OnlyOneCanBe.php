<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 13/08/2018
 * Time: 11:04
 */
namespace App\Validator;

use App\Validator\Constraints\OnlyOneCanBeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class OnlyOneCanBe
 * @package App\Validator
 */
class OnlyOneCanBe extends Constraint
{
    /**
     * @var string
     */
    public $atPath;

    /**
     * @var string
     */
    public $fieldName;

    /**
     * @var
     */
    public $fieldContent;

    /**
     * @var bool
     * If true, the validator throws if no table row will match this value after the change.
     */
    public $mustBeOne = false;

    /**
     * @var string
     */
    public $translationDomain = 'validators';

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return OnlyOneCanBeValidator::class;
    }

    /**
     * getRequiredOptions
     *
     * @return array
     */
    public function getRequiredOptions()
    {
        return [
            'atPath',
            'fieldName',
            'fieldContent',
        ];
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