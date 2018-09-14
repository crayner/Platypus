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
 * Date: 15/09/2018
 * Time: 08:00
 */
namespace App\Repository;

use App\Entity\FamilyMemberAdult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class FamilyMemberAdultRepository
 * @package App\Repository
 */
class FamilyMemberAdultRepository extends ServiceEntityRepository
{
    /**
     * FamilyMemberAdultRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FamilyMemberAdult::class);
    }
}
