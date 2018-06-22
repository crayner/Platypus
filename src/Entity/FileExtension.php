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
 * Date: 22/06/2018
 * Time: 11:31
 */
namespace App\Entity;

class FileExtension
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return FileExtension
     */
    public function setId(?int $id): FileExtension
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
     * @return FileExtension
     */
    public function setName(?string $name): FileExtension
    {
        $this->name = $name;
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
        'document','spreadsheet','presentation','graphics_design','video','audio','other',
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
     * @return FileExtension
     */
    public function setType(?string $type): FileExtension
    {
        $this->type = in_array($type, self::$typeList) ? $type : null ;;
        return $this;
    }

    /**
     * @var string
     */
    private $extension;

    /**
     * @return null|string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return FileExtension
     */
    public function setExtension(string $extension): FileExtension
    {
        $this->extension = $extension;
        return $this;
    }
}