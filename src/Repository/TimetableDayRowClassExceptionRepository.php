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
 * Date: 6/11/2018
 * Time: 07:21
 */
namespace App\Repository;

use App\Entity\TimetableDayRowClassException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TimetableDayRowClassExceptionRepository
 * @package App\Repository
 */
class TimetableDayRowClassExceptionRepository extends ServiceEntityRepository
{
    /**
     * TimetableDayRowClassExceptionRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TimetableDayRowClassException::class);
    }
}
