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
 * Date: 20/06/2018
 * Time: 12:12
 */
namespace App\Manager;

use App\Entity\AttendanceCode;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AttendanceCodeManager
 * @package App\Manager
 */
class AttendanceCodeManager
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
     * @var
     */
    private static $repository;

    /**
     * AttendanceCodeManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, TwigManager $twig)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->getMessageManager()->setDomain('School');
        $this->twig = $twig;
        self::$repository = $entityManager->getRepository(AttendanceCode::class);
    }

    public function find($id): ?AttendanceCode
    {
        return $this->getEntityManager()->getRepository(AttendanceCode::class)->find($id);
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
            $this->getMessageManager()->add('warning', 'attendance_code.not_found');
            return false;
        }
        
        if (!$entity->canDelete()){
            $this->getMessageManager()->add('warning', 'attendance_code.remove.locked', ['%{name}' => $entity->getName()]);
            return false;
        }
        try {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $this->getMessageManager()->add('danger', 'attendance_code.remove.error', ['%{message}' => $e->getMessage()]);
            return false;
        }
        $this->getMessageManager()->add('success', 'attendance_code.remove.success', ['%{name}' => $entity->getName()]);
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

    /**
     * getActiveAttendanceCodeList
     *
     * @return array
     */
    public static function getActiveAttendanceCodeList(): array
    {
        $x = self::$repository->findBy(['active' => true], ['sequence' => 'ASC']);
        $result = [];
        foreach($x as $entity)
            $result[$entity->getName()] = $entity->getId();

        return $result;
    }
}
