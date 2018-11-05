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
 * Date: 6/11/2018
 * Time: 07:22
 */

namespace App\Entity;

/**
 * Class TimetableDayRowClassException
 * @package App\Entity
 */
class TimetableDayRowClassException
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * getId
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int|null $id
     * @return TimetableDayRowClassException
     */
    public function setId(?int $id): TimetableDayRowClassException
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var TimetableDayRowClass|null
     */
    private $timetableDayRowClass;

    /**
     * getTimetableDayRowClass
     *
     * @return TimetableDayRowClass|null
     */
    public function getTimetableDayRowClass(): ?TimetableDayRowClass
    {
        return $this->timetableDayRowClass;
    }

    /**
     * setTimetableDayRowClass
     *
     * @param TimetableDayRowClass|null $timetableDayRowClass
     * @return TimetableDayRowClassException
     */
    public function setTimetableDayRowClass(?TimetableDayRowClass $timetableDayRowClass): TimetableDayRowClassException
    {
        $this->timetableDayRowClass = $timetableDayRowClass;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $person;

    /**
     * getPerson
     *
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * setPerson
     *
     * @param Person|null $person
     * @return TimetableDayRowClassException
     */
    public function setPerson(?Person $person): TimetableDayRowClassException
    {
        $this->person = $person;
        return $this;
    }
}