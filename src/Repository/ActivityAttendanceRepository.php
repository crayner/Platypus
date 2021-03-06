<?php
namespace App\Repository;

use App\Entity\ActivityAttendance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ActivityAttendanceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActivityAttendanceRepository extends ServiceEntityRepository
{
	/**
	 * ActivityAttendanceRepository constructor.
	 *
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, ActivityAttendance::class);
    }
}
