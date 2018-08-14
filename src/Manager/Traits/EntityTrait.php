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
 * Date: 23/06/2018
 * Time: 18:10
 */
namespace App\Manager\Traits;

use App\Manager\MessageManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PhpParser\Node\Expr\Cast\Object_;

/**
 * Trait EntityTrait
 * @package App\Manager
 */
trait EntityTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var EntityRepository
     */
    static private $entityRepository;

    /**
     * @var Object
     */
    private $entity;

    /**
     * EntityTrait constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        self::$entityRepository = $entityManager->getRepository($this->entityName);
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

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }

    /**
     * find
     *
     * @param $id
     * @return null|object
     * @throws \Exception
     */
    public function find($id): ?object
    {
        if ($id === 'Add')
            $this->entity = new $this->entityName();
        else
            $this->entity = $this->getEntityManager()->getRepository($this->getEntityName())->find($id);
        return $this->entity;
    }

    /**
     * delete
     *
     * @param $id
     * @throws \Exception
     */
    public function delete($id): void
    {
        $entity = $this->find($id);
        if (empty($entity))
        {
            $this->getMessageManager()->add('warning', 'entity.not_found', ['%{entity}' => $this->getEntityName()], 'System');
            return ;
        }

        if (method_exists($this, 'canDelete')) {
            if ($this->canDelete($entity)) {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
                $this->entity = null;
                return;

            }
        } elseif (method_exists($entity, 'canDelete')) {
            if ($entity->canDelete()) {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
                $this->entity = null;
                return;
            }
        } else {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
            $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
            $this->entity = null;
            return;

        }
        $this->getMessageManager()->add('warning', 'entity.remove.locked', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
    }

    /**
     * getEntityName
     *
     * @return string
     * @throws \Exception
     */
    public function getEntityName(): string
    {
        if (empty($this->entityName))
            throw new \Exception('You nust specify the entity class [$entityName] in ' . get_class($this));
        return $this->entityName;
    }

    /**
     * getEntity
     *
     * @return null|object
     */
    public function getEntity(): ?object
    {
        return $this->entity;
    }

    /**
     * @param Object $entity
     * @return EntityTrait
     */
    public function setEntity(object $entity): Object
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * getTransDomain
     *
     * @return string
     */
    public function getTransDomain(): string
    {
        if(empty($this->transDomain))
            return 'System';
        return $this->transDomain;
    }

    /**
     * saveEntity
     *
     * @return Object
     */
    public function saveEntity(): Object
    {
        $this->getEntityManager()->persist($this->getEntity());
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * getRepository
     *
     * @param string $className
     * @return EntityRepository
     * @throws \Exception
     */
    public function getRepository(string $className = ''): EntityRepository
    {
        $className = $className ?: $this->getEntityName();
        return $this->getEntityManager()->getRepository($className);
    }
}