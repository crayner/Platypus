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
 * Date: 14/08/2018
 * Time: 16:19
 */
namespace App\Manager;

use App\Entity\Alarm;
use App\Entity\AlarmConfirm;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;

/**
 * Class AlarmConfirmManager
 * @package App\Manager
 */
class AlarmConfirmManager
{
    use EntityTrait;

    private $entityName = AlarmConfirm::class;

    /**
     * findOneByAlarmPerson
     *
     * @param Alarm|null $alarm
     * @param Person|null $person
     * @return AlarmConfirm|null|object
     * @throws \Exception
     */
    public function findOneByAlarmPerson(?Alarm $alarm, ?Person $person)
    {
        $entity = $this->getRepository()->findOneBy(['alarm' => $alarm, 'person' => $person]);
        $entity = $entity ?: new AlarmConfirm();

        $this->setEntity($entity);
        $entity->setPerson($person)->setAlarm($alarm);
        return $entity;
    }
}