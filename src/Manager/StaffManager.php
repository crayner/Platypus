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
 * Date: 31/07/2018
 * Time: 17:45
 */
namespace App\Manager;

use App\Repository\PersonRepository;

/**
 * Class StaffManager
 * @package App\Manager
 */
class StaffManager
{
    /**
     * @var
     */
    private static $personRepository;

    /**
     * StaffManager constructor.
     * @param PersonRepository $personRepository
     */
    public function __construct(PersonRepository $personRepository)
    {
        self::$personRepository;
    }

    /**
     * staffList
     *
     * @return array
     */
    public static function getStaffList(): array
    {
        return [];
    }
}