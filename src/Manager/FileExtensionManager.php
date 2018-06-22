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
 * Date: 22/06/2018
 * Time: 12:13
 */
namespace App\Manager;

use App\Entity\FileExtension;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class FileExtensionManager
 * @package App\Manager
 */
class FileExtensionManager
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
     * FileExtensionManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, TwigManager $twig)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->getMessageManager()->setDomain('System');
        $this->twig = $twig;
    }

    public function find($id): ?FileExtension
    {
        return $this->getEntityManager()->getRepository(FileExtension::class)->find($id);
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
            $this->getMessageManager()->add('warning', 'file_extension.not_found');
            return false;
        }
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $this->getMessageManager()->add('danger', 'file_extension.remove.error', ['%{message}' => $e->getMessage()]);
            return false;
        }
        $this->getMessageManager()->add('success', 'file_extension.remove.success', ['%{name}' => $entity->getName()]);
        $this->getMessageManager()->add('danger', 'file_extension.remove.error', ['%{message}' => 'a dummy error message']);
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