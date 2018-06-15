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
 * Date: 14/06/2018
 * Time: 16:37
 */
namespace App\Manager;

use App\Entity\House;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class HouseManager
 * @package App\Manager
 */
class HouseManager
{
    /**
     * @var ArrayCollection
     */
    private $houses;

    /**
     * @param House $house
     *
     * @return HouseManager
     */
    public function addHouse(?House $house): HouseManager
    {
        if (empty($house) || $this->getHouses()->contains($house))
            return $this;

        $this->houses->add($house);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getHouses(): ArrayCollection
    {
        return $this->houses = $this->houses ?: new ArrayCollection();
    }

    /**
     * @param ArrayCollection $houses
     *
     * @return HouseManager
     */
    public function setHouses(ArrayCollection $houses): HouseManager
    {
        $this->houses = $houses;

        return $this;
    }

    /**
     * @param House $house
     *
     * @return HouseManager
     */
    public function removeHouse(?House $house): HouseManager
    {
        if (empty($house))
            return $this;

        $this->getHouses()->removeElement($house);
        $this->getEntityManager()->remove($house);
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
     * @param $id
     * @return House|null
     */
    public function find($id): ?House
    {
        if (intval($id) > 0)
            return $this->getEntityManager()->getRepository(House::class)->find($id);
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