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
 * Date: 25/06/2018
 * Time: 10:13
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

class ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setId(?int $id): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setName(?string $name): ExternalAssessment
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
     * @return ExternalAssessment
     */
    public function setNameShort(?string $nameShort): ExternalAssessment
    {
        $this->nameShort = $nameShort;
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
     * @return ExternalAssessment
     */
    public function setDescription(?string $description): ExternalAssessment
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var string|null
     */
    private $website;

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * setWebsite
     *
     * @param null|string $website
     * @return ExternalAssessment
     */
    public function setWebsite(?string $website): ExternalAssessment
    {
        $this->website = $website;
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
     * setActive
     *
     * @param bool|null $active
     * @return ExternalAssessment
     */
    public function setActive(?bool $active): ExternalAssessment
    {
        $this->active = $active ? true : false;;
        return $this;
    }

    /**
     * @var boolean
     */
    private $allowFileUpload;

    /**
     * isAllowFileUpload
     *
     * @return bool
     */
    public function isAllowFileUpload(): bool
    {
        return $this->allowFileUpload ? true : false;
    }

    /**
     * setAllowFileUpload
     *
     * @param bool|null $allowFileUpload
     * @return ExternalAssessment
     */
    public function setAllowFileUpload(?bool $allowFileUpload): ExternalAssessment
    {
        $this->allowFileUpload = $allowFileUpload ? true : false;;
        return $this;
    }

    /**
     * @var Collection
     */
    private $fields;

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        if (empty($this->fields))
            $this->fields = new ArrayCollection();

        if ($this->fields instanceof PersistentCollection)
            $this->fields->initialize();

        return $this->fields;
    }

    /**
     * setFields
     *
     * @param Collection|null $fields
     * @return ExternalAssessment
     */
    public function setFields(?Collection $fields): ExternalAssessment
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * addField
     *
     * @param ExternalAssessmentField|null $field
     * @param bool $add
     * @return ExternalAssessment
     */
    public function addField(?ExternalAssessmentField $field, $add = true): ExternalAssessment
    {
        if (empty($field) || $this->getFields()->contains($field))
            return $this;

        if ($add)
            $field->setExternalAssessment($this, false);

        $this->fields->add($field);

        return $this;
    }

    /**
     * removeField
     *
     * @param ExternalAssessmentField|null $field
     * @return ExternalAssessment
     */
    public function removeField(?ExternalAssessmentField $field): ExternalAssessment
    {
        $this->getFields()->removeElement($field);
        return $this;
    }

    /**
     * __toString
     *
     * @return null|string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @var Collection|null
     */
    private $categories;

    /**
     * getCategories
     *
     * @return Collection
     */
    public function getCategories(): Collection
    {
        if (empty($this->categories))
            $this->categories = new ArrayCollection();

        $iterator = $this->categories->getIterator();
        $iterator->uasort(
            function ($a, $b) {
                return ($a->getSequence() < $b->getSequence()) ? -1 : 1;
            }
        );

        $this->categories = new ArrayCollection(iterator_to_array($iterator, false));

        return $this->categories;
    }

    /**
     * setCategories
     *
     * @param Collection $categories
     * @return ExternalAssessment
     */
    public function setCategories(Collection $categories): ExternalAssessment
    {
        $this->categories = $categories;

        return $this;
    }
}