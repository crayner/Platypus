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
 * Time: 10:47
 */
namespace App\Entity;

use Hillrange\Security\Util\ParameterInjector;

class AttendanceCode
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
    public static function getDirectionList(): array
    {
        return self::$directionList;
    }

    /**
     * @return array
     */
    public static function getScopeList(): array
    {
        return self::$scopeList;
    }

    /**
     * @return array
     */
    public static function getRoleList(): array
    {
        $x = ParameterInjector::getParameter('security.hierarchy.roles');
        $result = [];
        foreach($x as $name=>$children)
            $result[$name] = $name;
        return $result;
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
     * @return AttendanceCode
     */
    public function setId(?int $id): AttendanceCode
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return AttendanceCode
     */
    public function setName(?string $name): AttendanceCode
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $nameShort;

    /**
     * @return null|string
     */
    public function getNameShort(): ?string
    {
        return $this->nameShort;
    }

    /**
     * @param null|string $nameShort
     * @return AttendanceCode
     */
    public function setNameShort(?string $nameShort): AttendanceCode
    {
        $this->nameShort = $nameShort;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var array
     */
    private static $typeList = [
        'core',
        'additional',
    ];

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return AttendanceCode
     */
    public function setType(?string $type): AttendanceCode
    {
        $this->type = in_array($type, self::$typeList) ? $type : 'core' ;
        return $this;
    }

    /**
     * @var null|string
     */
    private $direction;

    /**
     * @var array
     */
    private static $directionList = [
        'in',
        'out',
    ];

    /**
     * @return null|string
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param null|string $direction
     * @return AttendanceCode
     */
    public function setDirection(?string $direction): AttendanceCode
    {
        $this->direction = in_array($direction, self::$directionList) ? $direction : null;
        return $this;
    }

    /**
     * @var null|string
     */
    private $scope;

    /**
     * @var array
     */
    private static $scopeList = [
        'onsite',
        'onsite_late',
        'offsite',
        'offsite_left',
    ];

    /**
     * @return null|string
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param null|string $scope
     * @return AttendanceCode
     */
    public function setScope(?string $scope): AttendanceCode
    {
        $this->scope = in_array($scope, self::$scopeList) ? $scope : null;
        return $this;
    }

    /**
     * @var boolean
     */
    private $active;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active ? true : false;
    }

    /**
     * @param null|bool $active
     * @return AttendanceCode
     */
    public function setActive(?bool $active): AttendanceCode
    {
        $this->active = $active ? true : false;;
        return $this;
    }

    /**
     * @var boolean
     */
    private $reportable;

    /**
     * @return bool
     */
    public function isReportable(): bool
    {
        return $this->reportable ? true : false;
    }

    /**
     * @param null|bool $reportable
     * @return AttendanceCode
     */
    public function setReportable(?bool $reportable): AttendanceCode
    {
        $this->reportable = $reportable ? true : false;;
        return $this;
    }

    /**
     * @var boolean
     */
    private $future;

    /**
     * @return bool
     */
    public function isFuture(): bool
    {
        return $this->future ? true : false;
    }

    /**
     * @param null|bool $future
     * @return AttendanceCode
     */
    public function setFuture(?bool $future): AttendanceCode
    {
        $this->future = $future ? true : false;;
        return $this;
    }

    /**
     * @var integer|null
     */
    private $sequence;

    /**
     * @return int|null
     */
    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    /**
     * @param int|null $sequence
     * @return AttendanceCode
     */
    public function setSequence(?int $sequence): AttendanceCode
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @var null|string
     */
    private $role;

    /**
     * @return null|string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param null|string $role
     * @return AttendanceCode
     */
    public function setRole(?string $role): AttendanceCode
    {
        $this->role = $role;
        return $this;
    }

    /**
     * setDefaults
     *
     * @return AttendanceCode
     */
    public function setDefaults(): AttendanceCode
    {
        $this->setType($this->getType());
        return $this;
    }
}