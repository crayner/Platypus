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
 * Date: 10/09/2018
 * Time: 11:31
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Action
 * @package App\Entity
 */
class Action
{
    /**
     * @var integer|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getGroupByList(): array
    {
        sort(self::$groupByList);
        return self::$groupByList;
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
     * @return Action
     */
    public function setId(?int $id): Action
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $route;

    /**
     * @return null|string
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param null|string $route
     * @return Action
     */
    public function setRoute(?string $route): Action
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @var array|null
     */
    private $routeParams;

    /**
     * @return array
     */
    public function getRouteParams(): array
    {
        return $this->routeParams ?: [];
    }

    /**
     * @param array|null $routeParams
     * @return Action
     */
    public function setRouteParams(?array $routeParams): Action
    {
        $this->routeParams = $routeParams ?: [];
        return $this;
    }

    /**
     * @var PersonRole|null
     */
    private $role;

    /**
     * @return null|PersonRole
     */
    public function getRole(): ?PersonRole
    {
        return $this->role;
    }

    /**
     * @param null|PersonRole $role
     * @return Action
     */
    public function setRole(?PersonRole $role): Action
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @var Collection|null
     */
    private $personRoles;

    /**
     * @return Collection
     */
    public function getPersonRoles(): Collection
    {
        if (empty($this->personRoles))
            $this->personRoles = new ArrayCollection();

        if ($this->personRoles instanceof PersistentCollection)
            $this->personRoles->initialize();

        return $this->personRoles;
    }

    /**
     * @param null|Collection $personRoles
     * @return Action
     */
    public function setPersonRoles(?Collection $personRoles): Action
    {
        $this->personRoles = $personRoles;
        return $this;
    }

    /**
     * addPersonRole
     *
     * @param PersonRole|null $personRole
     * @return Action
     */
    public function addPersonRole(?PersonRole $personRole): Action
    {
        if (empty($personRole) || $this->getPersonRoles()->contains($personRole))
            return $this;

        $this->getPersonRoles()->add($personRole);

        return $this;
    }

    /**
     * removePersonRole
     *
     * @param PersonRole|null $personRole
     * @return Action
     */
    public function removePersonRole(?PersonRole $personRole): Action
    {
        $this->getPersonRoles()->removeElement($personRole);
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
     * @return Action
     */
    public function setName(?string $name): Action
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $groupBy;

    /**
     * @var array
     */
    private static $groupByList = [
        'school_admin',
        'system_admin',
        'personnel_admin',
        'timetable_admin',
    ];

    /**
     * @return null|string
     */
    public function getGroupBy(): ?string
    {
        return $this->groupBy;
    }

    /**
     * @param null|string $groupBy
     * @return Action
     */
    public function setGroupBy(?string $groupBy): Action
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * cleanSecondaryRoles
     *
     * @return Action
     */
    public function cleanSecondaryRoles(): Action
    {
        if ($this->getPersonRoles()->contains($this->getRole()))
            $this->removePersonRole($this->getRole());
        return $this;
    }

    /**
     * @var array|null
     */
    private $allowedCategories;

    /**
     * getAllowedCategories
     *
     * @return array
     */
    public function getAllowedCategories(): array
    {
        return $this->allowedCategories ?: [];
    }

    /**
     * setAllowedCategories
     *
     * @param array|null $allowedCategories
     * @return Action
     */
    public function setAllowedCategories(?array $allowedCategories): Action
    {
        $this->allowedCategories = $allowedCategories ?: [];
        return $this;
    }

    /**
     * @param array $groupByList
     */
    public function getVoterRouteParams(): array
    {
        $result = [];
        foreach($this->getRouteParams() as $param)
            $result[$param['name']] = $param['value'];
        return $result;
    }
}