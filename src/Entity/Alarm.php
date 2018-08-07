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
 * Date: 11/06/2018
 * Time: 16:39
 */
namespace App\Entity;

class Alarm
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getTypeList(): array
    {
        return self::$typeList;
    }

    /**
     * @return array
     */
    public static function getStatusList(): array
    {
        return self::$statusList;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Alarm
     */
    public function setId(?int $id): Alarm
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $person;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return Alarm
     */
    public function setPerson(?Person $person): Alarm
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var array
     */
    public static $typeList = [
        'none',
        'general',
        'lockdown-inplace',
        'lockdown-internal',
        'custom',
    ];

    /**
     * @return null|string
     */
    public function getType(): ?string
    {

        return $this->type = in_array($this->type, self::$typeList) ? $this->type : null ;
    }

    /**
     * @param null|string $type
     * @return Alarm
     */
    public function setType(?string $type): Alarm
    {
        $this->type = in_array($type, self::$typeList) ? $type : null ;;
        return $this;
    }

    /**
     * @var string|null
     */
    private $status;

    /**
     * @var array
     */
    public static $statusList = [
        'current',
        'past',
    ];

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status = in_array($this->status, self::$statusList) ? $this->status : 'past' ;
    }

    /**
     * @param null|string $status
     * @return Alarm
     */
    public function setStatus(?string $status): Alarm
    {
        $this->status = in_array($status, self::$statusList) ? $status : 'past' ;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $timestampStart;

    /**
     * @return \DateTime|null
     */
    public function getTimestampStart(): ?\DateTime
    {
        return $this->timestampStart;
    }

    /**
     * @param \DateTime|null $timestampStart
     * @return Alarm
     */
    public function setTimestampStart(?\DateTime $timestampStart): Alarm
    {
        $this->timestampStart = $timestampStart;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $timestampEnd;

    /**
     * @return \DateTime|null
     */
    public function getTimestampEnd(): ?\DateTime
    {
        return $this->timestampEnd;
    }

    /**
     * @param \DateTime|null $timestampEnd
     * @return Alarm
     */
    public function setTimestampEnd(?\DateTime $timestampEnd): Alarm
    {
        $this->timestampEnd = $timestampEnd;
        return $this;
    }

    /**
     * Alarm constructor.
     */
    public function __construct()
    {
        $this->type = 'none';
        $this->status = 'current';
    }
}