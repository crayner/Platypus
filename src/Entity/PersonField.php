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
 * Date: 9/09/2018
 * Time: 15:43
 */
namespace App\Entity;

/**
 * Class PersonField
 * @package App\Entity
 */
class PersonField
{
    /**
     * PersonField constructor.
     */
    public function __construct()
    {
        $this->setActive(true);
        $this->setRequired(true);
        $this->setForDataUpdater(true);
    }

    /**
     * @var int|null
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return PersonField
     */
    public function setId(?int $id): PersonField
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
     * @return PersonField
     */
    public function setName(?string $name): PersonField
    {
        $this->name = $name;
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
        return $this->active = $this->active ? true : false ;
    }

    /**
     * @param bool $active
     * @return PersonField
     */
    public function setActive(bool $active): PersonField
    {
        $this->active = $active ? true : false ;
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
     * @return PersonField
     */
    public function setDescription(?string $description): PersonField
    {
        $this->description = $description;
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
        'varchar','text','date','url','select','checkboxes'
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
     * @return PersonField
     */
    public function setType(?string $type): PersonField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @var array|null
     */
    private $options;

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?: [];
    }

    /**
     * @param array|null $options
     * @return PersonField
     */
    public function setOptions(?array $options): PersonField
    {
        $this->options = $options ?: [];
        return $this;
    }

    /**
     * @var boolean
     */
    private $required;

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required = $this->required ? true : false ;
    }

    /**
     * @param bool $required
     * @return PersonField
     */
    public function setRequired(bool $required): PersonField
    {
        $this->required = $required ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forStudent;

    /**
     * @return bool
     */
    public function isForStudent(): bool
    {
        return $this->forStudent = $this->forStudent ? true : false ;
    }

    /**
     * @param bool $forStudent
     * @return PersonField
     */
    public function setForStudent(bool $forStudent): PersonField
    {
        $this->forStudent = $forStudent ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forStaff;

    /**
     * @return bool
     */
    public function isForStaff(): bool
    {
        return $this->forStaff = $this->forStaff ? true : false ;
    }

    /**
     * @param bool $forStaff
     * @return PersonField
     */
    public function setForStaff(bool $forStaff): PersonField
    {
        $this->forStaff = $forStaff ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forParent;

    /**
     * @return bool
     */
    public function isForParent(): bool
    {
        return $this->forParent = $this->forParent ? true : false ;
    }

    /**
     * @param bool $forParent
     * @return PersonField
     */
    public function setForParent(bool $forParent): PersonField
    {
        $this->forParent = $forParent ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forOther;

    /**
     * @return bool
     */
    public function isForOther(): bool
    {
        return $this->forOther = $this->forOther ? true : false ;
    }

    /**
     * @param bool $forOther
     * @return PersonField
     */
    public function setForOther(bool $forOther): PersonField
    {
        $this->forOther = $forOther ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forApplicationForm;

    /**
     * @return bool
     */
    public function isForApplicationForm(): bool
    {
        return $this->forApplicationForm = $this->forApplicationForm ? true : false ;
    }

    /**
     * @param bool $forApplicationForm
     * @return PersonField
     */
    public function setForApplicationForm(bool $forApplicationForm): PersonField
    {
        $this->forApplicationForm = $forApplicationForm ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forDataUpdater;

    /**
     * @return bool
     */
    public function isForDataUpdater(): bool
    {
        return $this->forDataUpdater = $this->forDataUpdater ? true : false ;
    }

    /**
     * @param bool $forDataUpdater
     * @return PersonField
     */
    public function setForDataUpdater(bool $forDataUpdater): PersonField
    {
        $this->forDataUpdater = $forDataUpdater ? true : false ;
        return $this;
    }

    /**
     * @var boolean
     */
    private $forPublicRegistration;

    /**
     * @return bool
     */
    public function isForPublicRegistration(): bool
    {
        return $this->forPublicRegistration = $this->forPublicRegistration ? true : false ;
    }

    /**
     * @param bool $forPublicRegistration
     * @return PersonField
     */
    public function setForPublicRegistration(bool $forPublicRegistration): PersonField
    {
        $this->forPublicRegistration = $forPublicRegistration ? true : false ;
        return $this;
    }
}