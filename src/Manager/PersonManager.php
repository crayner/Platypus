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
 * Date: 10/08/2018
 * Time: 15:11
 */
namespace App\Manager;

use App\Entity\Person;
use App\Manager\Traits\EntityTrait;

/**
 * Class PersonManager
 * @package App\Manager
 */
class PersonManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Person::class;
}