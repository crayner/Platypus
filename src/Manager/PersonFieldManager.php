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
 * Date: 9/09/2018
 * Time: 16:04
 */
namespace App\Manager;

use App\Entity\PersonField;
use App\Manager\Traits\EntityTrait;

/**
 * Class PersonFieldManager
 * @package App\Manager
 */
class PersonFieldManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = PersonField::class;
}