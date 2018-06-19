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
 * Date: 19/06/2018
 * Time: 10:46
 */
namespace App\Manager;

use App\Entity\INDescriptor;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class IndividualNeedDescriptorManager
 * @package App\Manager
 */
class IndividualNeedDescriptorManager
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
     * @var TwigManager
     */
    private $twig;

    /**
     * IndividualNeedDescriptorManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, TwigManager $twig)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->getMessageManager()->setDomain('System');
        $this->twig = $twig;
    }

    public function find($id): ?INDescriptor
    {
        return $this->getEntityManager()->getRepository(INDescriptor::class)->find($id);
    }

    /**
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
     * remove
     *
     * @param $id
     * @return bool
     */
    public function remove($id): bool
    {
        $entity = $this->find($id);
        if (empty($entity)) {
            $this->getMessageManager()->add('warning', 'individual_need_descriptor.not_found');
            return false;
        }
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $this->getMessageManager()->add('danger', 'individual_need_descriptor.remove.error', ['%{message}' => $e->getMessage()]);
            return false;
        }
        $this->getMessageManager()->add('success', 'individual_need_descriptor.remove.success', ['%{name}' => $entity->getName()]);
        return true;
    }

    /**
     * getMessages
     *
     * @return null|string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getMessages(): ?string
    {
        return $this->getMessageManager()->renderView($this->twig->getTwig());
    }
}