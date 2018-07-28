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
 * Date: 30/06/2018
 * Time: 12:19
 */
namespace App\Manager;

use App\Entity\ExternalAssessmentCategory;
use App\Manager\Traits\EntityTrait;

/**
 * Class ExternalAssessmentCategoryManager
 * @package App\Manager
 */
class ExternalAssessmentCategoryManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ExternalAssessmentCategory::class;
}