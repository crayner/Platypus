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
     * EntityTrait constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
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
            return new $this->entityName();

        return $this->getEntityManager()->getRepository($this->getEntityName())->find($id);
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
                return;

            }
        } elseif (method_exists($entity, 'canDelete')) {
            if ($entity->canDelete()) {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
                $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
                return;
            }
        } else {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
            $this->getMessageManager()->add('success', 'entity.removed.success', ['%{entity}' => $this->getEntityName(), '%{name}' => $entity->__toString()], 'System');
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

}