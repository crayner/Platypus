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
 * Date: 25/09/2018
 * Time: 10:02
 */
namespace App\Repository;

use App\Entity\CourseClassPerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CourseClassPersonRepository
 * @package App\Repository
 */
class CourseClassPersonRepository extends ServiceEntityRepository
{
    /**
     * ActionRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CourseClassPerson::class);
    }
}
