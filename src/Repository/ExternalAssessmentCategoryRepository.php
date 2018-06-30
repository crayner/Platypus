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
 * Date: 30/06/2018
 * Time: 11:32
 */
namespace App\Repository;

use App\Entity\ExternalAssessmentCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ExternalAssessmentCategoryRepository
 * @package App\Repository
 */
class ExternalAssessmentCategoryRepository extends ServiceEntityRepository
{
    /**
     * ExternalAssessmentCategoryRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExternalAssessmentCategory::class);
    }
}
