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
 * Date: 29/06/2018
 * Time: 14:02
 */
namespace App\Manager;

use App\Entity\ExternalAssessmentField;
use App\Manager\Traits\EntityTrait;

/**
 * Class ExternalAssessmentFieldManager
 * @package App\Manager
 */
class ExternalAssessmentFieldManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ExternalAssessmentField::class;
}