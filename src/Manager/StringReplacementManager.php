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
 * Date: 2/08/2018
 * Time: 12:12
 */
namespace App\Manager;

use App\Entity\StringReplacement;
use App\Manager\Traits\EntityTrait;

class StringReplacementManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = StringReplacement::class;

    /**
     * @var string
     */
    private $transDomain = 'System';
}