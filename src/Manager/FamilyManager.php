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
 * Date: 14/09/2018
 * Time: 13:21
 */
namespace App\Manager;

use App\Entity\Family;
use App\Manager\Traits\EntityTrait;

/**
 * Class FamilyManager
 * @package App\Manager
 */
class FamilyManager
{
    use EntityTrait;

    /**
     * @var string
     */
    protected $entityName = Family::class;
}