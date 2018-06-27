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
 * Date: 27/06/2018
 * Time: 15:17
 */
namespace App\Manager;

use App\Entity\ScaleGrade;
use App\Manager\Traits\EntityTrait;

/**
 * Class ScaleGradeManager
 * @package App\Manager
 */
class ScaleGradeManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ScaleGrade::class;
}