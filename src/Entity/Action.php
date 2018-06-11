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
 * Time: 15:48
 */
namespace App\Entity;

class Action
{
    /**
     * @var integer|null
     */
    private $id;

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
     * @var integer
     */
    private $precedence;

    /**
     * @return int
     */
    public function getPrecedence(): int
    {
        return $this->precedence ?: 0;
    }

    /**
     * @param int|null $precedence
     * @return Action
     */
    public function setPrecedence(?int $precedence): Action
    {
        $this->precedence = $precedence ?: 0;
        return $this;
    }

    /**
     * @var string|null
     */
    private $category;

    /**
     * @return null|string
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param null|string $category
     * @return Action
     */
    public function setCategory(?string $category): Action
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @var string|null
     */
    private $description;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return Action
     */
    public function setDescription(?string $description): Action
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var array
     */
    private $URLList;

    /**
     * @return array
     */
    public function getURLList(): array
    {
        return $this->URLList;
    }

    /**
     * @param array $URLList
     * @return Action
     */
    public function setURLList(array $URLList): Action
    {
        $this->URLList = $URLList;
        return $this;
    }

    /**
     * @var string|null
     */
    private $entryURL;

    /**
     * @return null|string
     */
    public function getEntryURL(): ?string
    {
        return $this->entryURL;
    }

    /**
     * @param null|string $entryURL
     * @return Action
     */
    public function setEntryURL(?string $entryURL): Action
    {
        $this->entryURL = $entryURL;
        return $this;
    }

    /**
     * @var bool
     */
    private $entrySidebar;

    /**
     * @return bool
     */
    public function isEntrySidebar(): bool
    {
        return $this->entrySidebar;
    }

    /**
     * @param bool $entrySidebar
     * @return Action
     */
    public function setEntrySidebar(bool $entrySidebar): Action
    {
        $this->entrySidebar = $entrySidebar;
        return $this;
    }

    /**
     * @var bool
     */
    private $menuShow;

    /**
     * @return bool
     */
    public function isMenuShow(): bool
    {
        return $this->menuShow;
    }

    /**
     * @param bool $menuShow
     * @return Action
     */
    public function setMenuShow(bool $menuShow): Action
    {
        $this->menuShow = $menuShow;
        return $this;
    }

    /**
     * @return bool
     */
    private $defaultPermissionAdmin;

    /**
     * @return mixed
     */
    public function getDefaultPermissionAdmin()
    {
        return $this->defaultPermissionAdmin;
    }

    /**
     * @param mixed $defaultPermissionAdmin
     * @return Action
     */
    public function setDefaultPermissionAdmin($defaultPermissionAdmin)
    {
        $this->defaultPermissionAdmin = $defaultPermissionAdmin;
        return $this;
    }

    /**
     * @return bool
     */
    private $defaultPermissionTeacher;

    /**
     * @return mixed
     */
    public function getDefaultPermissionTeacher()
    {
        return $this->defaultPermissionTeacher;
    }

    /**
     * @param mixed $defaultPermissionTeacher
     * @return Action
     */
    public function setDefaultPermissionTeacher($defaultPermissionTeacher)
    {
        $this->defaultPermissionTeacher = $defaultPermissionTeacher;
        return $this;
    }

    /**
     * @return bool
     */
    private $defaultPermissionStudent;

    /**
     * @return mixed
     */
    public function getDefaultPermissionStudent()
    {
        return $this->defaultPermissionStudent;
    }

    /**
     * @param mixed $defaultPermissionStudent
     * @return Action
     */
    public function setDefaultPermissionStudent($defaultPermissionStudent)
    {
        $this->defaultPermissionStudent = $defaultPermissionStudent;
        return $this;
    }

    /**
     * @return bool
     */
    private $defaultPermissionSupport;

    /**
     * @return mixed
     */
    public function getDefaultPermissionSupport()
    {
        return $this->defaultPermissionSupport;
    }

    /**
     * @param mixed $defaultPermissionSupport
     * @return Action
     */
    public function setDefaultPermissionSupport($defaultPermissionSupport)
    {
        $this->defaultPermissionSupport = $defaultPermissionSupport;
        return $this;
    }

    /**
     * @return bool
     */
    private $categoryPermissionStaff;

    /**
     * @return mixed
     */
    public function getCategoryPermissionStaff()
    {
        return $this->categoryPermissionStaff;
    }

    /**
     * @param mixed $categoryPermissionStaff
     * @return Action
     */
    public function setCategoryPermissionStaff($categoryPermissionStaff)
    {
        $this->categoryPermissionStaff = $categoryPermissionStaff;
        return $this;
    }

    /**
     * @return bool
     */
    private $categoryPermissionStudent;

    /**
     * @return mixed
     */
    public function getCategoryPermissionStudent()
    {
        return $this->categoryPermissionStudent;
    }

    /**
     * @param mixed $categoryPermissionStudent
     * @return Action
     */
    public function setCategoryPermissionStudent($categoryPermissionStudent)
    {
        $this->categoryPermissionStudent = $categoryPermissionStudent;
        return $this;
    }

    /**
     * @var bool
     */
    private $categoryPermissionParent;

    /**
     * @return bool
     */
    public function isCategoryPermissionParent(): bool
    {
        return $this->categoryPermissionParent;
    }

    /**
     * @param bool $categoryPermissionParent
     * @return Action
     */
    public function setCategoryPermissionParent(bool $categoryPermissionParent): Action
    {
        $this->categoryPermissionParent = $categoryPermissionParent;
        return $this;
    }

    /**
     * @var bool
     */
    private $categoryPermissionOther;

    /**
     * @return bool
     */
    public function isCategoryPermissionOther(): bool
    {
        return $this->categoryPermissionOther;
    }

    /**
     * @param bool $categoryPermissionOther
     * @return Action
     */
    public function setCategoryPermissionOther(bool $categoryPermissionOther): Action
    {
        $this->categoryPermissionOther = $categoryPermissionOther;
        return $this;
    }

    /**
     * @var Module|null
     */
    private $module;

    /**
     * @return Module|null
     */
    public function getModule(): ?Module
    {
        return $this->module;
    }

    /**
     * @param Module|null $module
     * @return Action
     */
    public function setModule(?Module $module): Action
    {
        $this->module = $module;
        return $this;
    }
}