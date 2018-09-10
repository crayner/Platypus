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
 * Date: 10/09/2018
 * Time: 11:38
 */
namespace App\Manager;

use App\Entity\Action;
use App\Manager\Traits\EntityTrait;

class ActionManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Action::class;
}