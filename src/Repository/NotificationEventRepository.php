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
 * Date: 2/08/2018
 * Time: 17:17
 */
namespace App\Repository;

use App\Entity\NotificationEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class NotificationEventRepository
 * @package App\Repository
 */
class NotificationEventRepository extends ServiceEntityRepository
{
    /**
     * NotificationEventRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotificationEvent::class);
    }
}
