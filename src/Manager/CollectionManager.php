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
 * Date: 15/06/2018
 * Time: 14:30
 */

namespace App\Manager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CollectionManager
 * @package App\Manager
 */
class CollectionManager
{
    /**
     * @var ArrayCollection
     */
    private $collection;

    /**
     * @param House $entity
     *
     * @return CollectionManager
     */
    public function addEntity($entity): CollectionManager
    {
        if (empty($entity) || $this->getCollection()->contains($entity))
            return $this;

        $this->collection->add($entity);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCollection(): ArrayCollection
    {
        return $this->collection = $this->collection ?: new ArrayCollection();
    }

    /**
     * @param ArrayCollection $collection
     *
     * @return CollectionManager
     */
    public function setCollection(ArrayCollection $collection): CollectionManager
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @param $entity
     *
     * @return CollectionManager
     */
    public function removeEntity($entity): CollectionManager
    {
        if (empty($entity))
            return $this;

        $this->getCollection()->removeElement($entity);
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * HouseManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * find
     *
     * @param int|null $id
     * @param string $entityClassName
     * @return object|null
     */
    public function find(?int $id, string $entityClassName)
    {
        if (intval($id) > 0)
            return $this->getEntityManager()->getRepository($entityClassName)->find($id);
        return null;
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}