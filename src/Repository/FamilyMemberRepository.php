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
 * Date: 22/08/2018
 * Time: 10:48
 */
namespace App\Repository;

use App\Entity\FamilyMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class FamilyMemberRepository
 * @package App\Repository
 */
class FamilyMemberRepository extends ServiceEntityRepository
{
    /**
     * FamilyMemberRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FamilyMember::class);
    }
}
