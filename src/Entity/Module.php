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

class Module
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
     * @return Module
     */
    public function setId(?int $id): Module
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
     * @return Module
     */
    public function setName(?string $name): Module
    {
        $this->name = $name;
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
     * @return Module
     */
    public function setDescription(?string $description): Module
    {
        $this->description = $description;
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
     * @return Module
     */
    public function setEntryURL(?string $entryURL): Module
    {
        $this->entryURL = $entryURL;
        return $this;
    }

    private static $typeList = [
        'core',
        'additional',
    ];
    /**
     * @var string|null
     */
    private $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type ?: 'core';
    }

    /**
     * @param null|string $type
     * @return Module
     */
    public function setType(?string $type): Module
    {
        $this->type = $type === 'additional' ? 'additional' : 'core';
        return $this;
    }

    /**
     * @var bool
     */
    private $active;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Module
     */
    public function setActive(bool $active): Module
    {
        $this->active = $active;
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
     * @return Module
     */
    public function setCategory(?string $category): Module
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @var string|null
     */
    private $version;

    /**
     * @return null|string
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param null|string $version
     * @return Module
     */
    public function setVersion(?string $version): Module
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @var string|null
     */
    private $author;

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param null|string $author
     * @return Module
     */
    public function setAuthor(?string $author): Module
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @var string|null
     */
    private $url;

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     * @return Module
     */
    public function setUrl(?string $url): Module
    {
        $this->url = $url;
        return $this;
    }
}