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
 * Date: 13/08/2018
 * Time: 10:07
 */
namespace App\Entity;

/**
 * Class Staff
 * @package App\Entity
 */
class Staff
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
     * @return Staff
     */
    public function setId(?int $id): Staff
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var null|Person
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
     * @return Staff
     */
    public function setPerson(?Person $person): Staff
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var boolean
     */
    private $smartWorkflowHelp;

    /**
     * @return bool
     */
    public function isSmartWorkflowHelp(): bool
    {
        return $this->smartWorkflowHelp ? true : false ;
    }

    /**
     * @param bool|null $smartWorkflowHelp
     * @return Staff
     */
    public function setSmartWorkflowHelp(?bool $smartWorkflowHelp): Staff
    {
        $this->smartWorkflowHelp = $smartWorkflowHelp ? true : false;
        return $this;
    }

    /**
     * Staff constructor.
     */
    public function __construct()
    {
        $this->smartWorkflowHelp = true;
    }
}