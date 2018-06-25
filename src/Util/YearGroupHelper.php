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
 * Date: 25/06/2018
 * Time: 10:05
 */
namespace App\Util;

use App\Entity\YearGroup;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class YearGroupHelper
 * @package App\Util
 */
class YearGroupHelper
{
    /**
     * @var EntityManagerInterface
     */
    private static $entityManager;

    /**
     * YearGroupHelper constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * getYearGroupList
     *
     * @param string $value
     * @param string $label
     * @return array
     */
    public static function getYearGroupList(string $value = 'id', string $label = 'name'): array
    {
        $result = [];
        $x = self::$entityManager->getRepository(YearGroup::class)->findBy([], ['sequence' => 'ASC']);
        $value = 'get' . ucfirst($value);
        $label = 'get' . ucfirst($label);
        foreach($x as $yearGroup)
        {
            $result['year_group.label.'.$yearGroup->$label()] = $yearGroup->$value();
        }
        return $result;
    }
}