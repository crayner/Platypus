<?php
namespace App\Repository;

use App\Entity\DayOfWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * DayOfWeekRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DayOfWeekRepository extends ServiceEntityRepository
{
	/**
	 * DayOfWeekRepository constructor.
	 *
	 * @param RegistryInterface $registry
	 */
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, DayOfWeek::class);
    }
}