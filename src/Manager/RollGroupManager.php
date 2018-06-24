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
use App\Manager\Traits\EntityTrait;
use App\Util\SchoolYearHelper;

/**
 * Class RollGroupManager
 * @package App\Manager
 */
class RollGroupManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = RollGroup::class;

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