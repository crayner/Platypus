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
 * Date: 14/11/2018
 * Time: 10:10
 */
namespace App\Repository;

use App\Entity\AttendanceLogCourseClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class AttendanceLogCourseClassRepository
 * @package App\Repository
 */
class AttendanceLogCourseClassRepository extends ServiceEntityRepository
{
    /**
     * AttendanceLogCourseClassRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AttendanceLogCourseClass::class);
    }
}
