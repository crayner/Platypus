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
 * Time: 10:27
 */
namespace App\Repository;

use App\Entity\ExternalAssessment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ExternalAssessmentRepository
 * @package App\Repository
 */
class ExternalAssessmentRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExternalAssessment::class);
    }
}
