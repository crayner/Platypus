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

use App\Entity\StudentNoteCategory;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class StudentNoteCategoryManager
 * @package App\Manager
 */
class StudentNoteCategoryManager
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
     * StudentNoteCategoryManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, TwigManager $twig)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->getMessageManager()->setDomain('Student');
        $this->twig = $twig;
    }

    public function find($id): ?StudentNoteCategory
    {
        return $this->getEntityManager()->getRepository(StudentNoteCategory::class)->find($id);
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
        $snc = $this->find($id);
        if (empty($snc)) {
            $this->getMessageManager()->add('warning', 'student_note_category.not_found');
            return false;
        }
        try {
            $this->getEntityManager()->remove($snc);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            $this->getMessageManager()->add('danger', 'student_note_category.remove.error', ['%{message}' => $e->getMessage()]);
            return false;
        }
        $this->getMessageManager()->add('success', 'student_note_category.remove.success', ['%{name}' => $snc->getName()]);
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