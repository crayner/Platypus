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
 * Time: 08:44
 */
namespace App\Manager;

use App\Entity\RollGroup;
use App\Util\SchoolYearHelper;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RollGroupManager
 * @package App\Manager
 */
class RollGroupManager
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
     * RollGroupManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param SchoolYearHelper $helper
     */
    public function __construct(EntityManagerInterface $entityManager, SchoolYearHelper $helper, MessageManager $messageManager)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->messageManager->setDomain('School');
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
     * find
     *
     * @param $id
     * @return RollGroup|null
     */
    public function find($id): ?RollGroup
    {
        if ($id === 'Add')
            return new RollGroup();
        return $this->getEntityManager()->getRepository(RollGroup::class)->find($id);
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }


    /**
     * delete
     *
     * @param $id
     */
    public function delete($id): void
    {
        $entity = $this->find($id);
        if (empty($entity))
        {
            $this->getMessageManager()->add('warning', 'school.roll_group.not_found', [], 'School');
            return ;
        }

        if ($entity->canDelete())
        {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
            $this->getMessageManager()->add('success', 'school.roll_group.removed.success', ['%{name}' => $entity->getName()], 'School');
            return ;
        }
        $this->getMessageManager()->add('warning', 'school.roll_group.remove.locked', ['%{name}' => $entity->getName()], 'School');
    }

    /**
     * copyToNextYear
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function copyToNextYear()
    {
        if (! $this->isNextYearEmpty())
        {
            $this->getMessageManager()->add('warning', 'school.roll_groups.copy.locked', ['%{year}' => SchoolYearHelper::getNextSchoolYear()->getName()]);
            return;
        }

        $exists = $this->getEntityManager()->getRepository(RollGroup::class)->findBySchoolYear(SchoolYearHelper::getCurrentSchoolYear());

        $count = 0;
        foreach($exists as $rollGroup)
        {
            $rollGroup->getTutors(); // Initialise the Collection so that they copy
            $rollGroup->getAssistants();
            $entity = clone $rollGroup;
            $entity->setId(null);
            $entity->setSchoolYear(SchoolYearHelper::getNextSchoolYear());

            $this->getEntityManager()->persist($entity);
            $count++;
        }

        $this->getEntityManager()->flush();

        $this->getMessageManager()->add('success', 'school.roll_groups.copy_next_year.success', ['transChoice' => $count, '%{year}' => SchoolYearHelper::getNextSchoolYear()->getName()]);
    }

    /**
     * isNextYearEmpty
     *
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function isNextYearEmpty(): bool
    {
        $exists = $this->getEntityManager()->getRepository(RollGroup::class)->findBySchoolYear(SchoolYearHelper::getNextSchoolYear());

        if (empty($exists))
            return true;

        return false;
    }
}