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

use App\Listener\InstallSubscriber;
use App\Manager\MessageManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

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
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * EntityTrait constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        self::$entityRepository = $this->getRepository();
        $this->authorizationChecker = $authorizationChecker;
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
     * @return Object
     * @throws \Exception
     */
    public function find($id)
    {
        $this->entity = null;
        if ($id === 'Add')
            $this->entity = new $this->entityName();
        else {
            if ($this->getRepository() !== null)
                $this->entity = $this->getRepository()->find($id);
        }
        return $this->entity;
    }

    /**
     * delete
     *
     * @param $id
     * @return object
     * @throws \Exception
     */
    public function delete($id)
    {
        if ($id === 'ignore') return $this->getEntity();
        if ($id instanceof $this->entityName)
        {
            $this->setEntity($id);
            $entity = $id;
            $id = $entity->getId();
        } else
            $entity = $this->find($id);
        if (empty($entity))
        {
            $this->getMessageManager()->add('warning', 'entity.not_found', ['%{entity}' => $this->getEntityName()], 'System');
            return $entity;
        }

        if (method_exists($this, 'canDelete')) {
            if ($this->canDelete($entity)) {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
                $this->entity = null;
                return $entity;

            }
        } elseif (method_exists($entity, 'canDelete')) {
            if ($entity->canDelete()) {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
                $this->entity = null;
                return $entity;
            }
        } else {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
            $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
            $this->entity = null;
            return $entity;

        }
        $this->getMessageManager()->add('warning', 'entity.remove.locked', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');

        return $entity;
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
    public function getEntity($entity = null): ?object
    {
        if ($entity instanceof $this->entityName)
            $this->setEntity($entity);
        return $this->entity;
    }

    /**
     * @param DoctrineEntity $entity
     * @return EntityTrait
     */
    public function setEntity($entity)
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
    public function saveEntity()
    {
        $this->getEntityManager()->persist($this->getEntity());
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * getRepository
     *
     * @param string $className
     * @return ObjectRepository|null
     * @throws \Exception
     */
    public function getRepository(?string $className = ''): ?ObjectRepository
    {
        if ($this->isValidEntityManager()) {
            $className = $className ?: $this->getEntityName();
            return $this->getEntityManager()->getRepository($className);
        }
        return null;
    }

    /**
     * @var bool|null
     */
    private $validEntityManager;

    /**
     * isValidEntityManager
     *
     * @return bool
     */
    public function isValidEntityManager(): bool
    {
        if (! is_null($this->validEntityManager))
            return $this->validEntityManager;
        if (InstallSubscriber::isInstalling() || empty($this->getEntityManager()->getConnection()->getParams()['dbname']))
            return $this->validEntityManager = false;
        return $this->validEntityManager = true;
    }

    /**
     * isValidEntity
     *
     * @return bool
     */
    public function isValidEntity(): bool
    {
        if ($this->getEntity() instanceof $this->entityName && intval($this->getEntity()->getId()) > 0)
            return true;

        // a new entity is NOT valid until saved.
        return false;
    }

    /**
     * getAuthorizationChecker
     *
     * @return AuthorizationCheckerInterface
     */
    public function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->authorizationChecker;
    }
}
