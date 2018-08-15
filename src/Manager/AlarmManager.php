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
 * Date: 4/08/2018
 * Time: 09:35
 */
namespace App\Manager;

use App\Entity\Alarm;
use App\Entity\AlarmConfirm;
use App\Entity\Person;
use App\Entity\Staff;
use App\Manager\Traits\EntityTrait;

/**
 * Class AlarmManager
 * @package App\Manager
 */
class AlarmManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Alarm::class;

    /**
     * findCurrent
     *
     * @return Alarm
     */
    public function findCurrent(): Alarm
    {
        $alarm = $this->getEntityManager()->getRepository($this->getEntityName())->findOneByStatus('current');

        $alarm = $alarm ?: new Alarm();

        return $this
            ->setEntity($alarm)
            ->getEntity();
    }

    /**
     * getAlarmConfirmList
     *
     * @return arrayr5
     */
    public function getAlarmConfirmList(): array
    {
        $query = $this->getEntityManager()->getRepository(Staff::class)->createQueryBuilder('s')
            ->select(["CONCAT(p.surname,': ',p.preferredName) AS fullName", 'p.id'])
            ->leftJoin('s.person','p')
            ->orderBy('p.surname', 'ASC')
            ->addOrderBy('p.preferredName', 'ASC')
        ;

        $result = $query->getQuery()->getArrayResult();
        return $result;
    }
}