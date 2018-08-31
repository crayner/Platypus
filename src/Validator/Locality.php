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
 * Date: 31/08/2018
 * Time: 14:06
 */
namespace App\Validator;

use App\Validator\Constraints\LocalityValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class Locality
 * @package App\Validator
 */
class Locality extends Constraint
{
    /**
     * @var string
     */
    public $rule;

    /**
     * validatedBy
     *
     * @return string
     */
    public function validatedBy()
    {
        return LocalityValidator::class;
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

    /**
     * getRequiredOptions
     *
     * @return array
     */
    public function getRequiredOptions()
    {
        return [
            'rule'
        ];
    }
}