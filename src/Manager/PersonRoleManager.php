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
 * Date: 27/08/2018
 * Time: 15:48
 */

namespace App\Manager;


use App\Entity\PersonRole;
use App\Manager\Traits\EntityTrait;
use App\Util\StringHelper;

class PersonRoleManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = PersonRole::class;

    /**
     * getPersonRoles
     *
     * @return array
     */
    public function getPersonRoles(): array
    {
        $results = $this->getRepository()->createQueryBuilder('r', 'r.id')
            ->select('r.category')
            ->orderBy('r.category', 'ASC')
            ->getQuery()
            ->getArrayResults();
        return $results;
    }

    /**
     * getPersonRoleList
     *
     * @return array
     * @throws \Exception
     */
    public function getPersonRoleList(): array
    {
        $result = $this->getRepository()->createQueryBuilder('r', 'r.id')
            ->orderBy('r.category')
            ->addOrderBy('r.name')
            ->select('r.id, r.name, r.category')
            ->getQuery()
            ->getArrayResult();

        $prl = [];
        $cat = '';
        foreach($result as $q=>$w) {
            if ($cat !== $w['category']) {
                $cat = $w['category'];
                $prl['person_role.category.' . StringHelper::safeString($w['category'])] = [];
            }
            $prl['person_role.category.' . StringHelper::safeString($w['category'])]['person_role.' . StringHelper::safeString($w['name'], true)] = $w['id'];
        }
        return $prl;
    }
}