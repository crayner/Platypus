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
 * Date: 20/09/2018
 * Time: 14:46
 */
namespace App\Repository;

use App\Entity\StudentEnrolment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class StudentEnrolmentRepository
 * @package App\Repository
 */
class StudentEnrolmentRepository extends ServiceEntityRepository
{
    /**
     * StringReplacementRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentEnrolment::class);
    }
}
