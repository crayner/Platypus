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
 * Date: 28/09/2018
 * Time: 13:38
 */
namespace App\Entity;

use App\Util\PhotoHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Hillrange\Form\Validator\Colour;
use Hillrange\Form\Validator\Integer;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class Setting
 * @package App\Entity
 */
class Setting
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @return array
     */
    public static function getSettingTypeList(): array
    {
        return self::$settingTypeList;
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
     * @return Setting
     */
    public function setId(?int $id): Setting
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var array
     */
    private static $settingTypeList = [
        'array',
        'blob',
        'boolean',
        'colour',
        'currency',
        'date',
        'email',
        'enum',
        'file',
        'html',
        'image',
        'integer',
        'multiEnum',
        'number',
        'string',
        'system',
        'text',
        'twig',
        'url',
    ];

    /**
     * @var string|null
     */
    private $settingType;

    /**
     * @return null|string
     */
    public function getSettingType(): ?string
    {
        return $this->settingType;
    }

    /**
     * @param string $settingType
     * @return Setting
     */
    public function setSettingType(string $settingType): Setting
    {
        if (! in_array($settingType, self::getSettingTypeList()))
            trigger_error(sprintf('The setting type %s has not been defined.  Allowed types are %s', $settingType, implode(',',self::getSettingTypeList())));
        $this->settingType = $settingType;
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
     * @return Setting
     */
    public function setName(?string $name): Setting
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var string|null
     */
    private $displayName;

    /**
     * @return null|string
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param null|string $displayName
     * @return Setting
     */
    public function setDisplayName(?string $displayName): Setting
    {
        $this->displayName = $displayName;
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
     * @return Setting
     */
    public function setDescription(?string $description): Setting
    {
        if (empty($description))
            $description = '';
        $this->description = $description;
        return $this;
    }

    /**
     * @var mixed|null
     */
    private $value;

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed|null $value
     * @return Setting
     */
    public function setValue($value): Setting
    {
        if (($this->getSettingType() === 'image' || $this->getSettingType() === 'file') && $value !== $this->getValue())
            PhotoHelper::deletePhotoFile($this->getValue());

        $this->value = $value;
        return $this;
    }

    /**
     * @var array|null
     */
    private $validators;

    /**
     * @return array|null
     */
    public function getValidators(): array
    {
        $this->validators = $this->validators ?: [];

        $w = new ArrayCollection($this->validators);

        switch ($this->getSettingType()) {
            case 'url':
                $x = new Url();
                if (! $w->contains($x))
                    $w->add($x);
                break;
            case 'string':
                $x = new Length(['max' => 25]);
                if (! $w->contains($x))
                    $w->add($x);
                break;
            case 'text':
                $x = new Length(['max' => 255]);
                if (! $w->contains($x))
                    $w->add($x);
                break;
            case 'integer':
                $x = new Integer();
                if (! $w->contains($x))
                    $w->add($x);
                break;
            case 'colour':
                $x = new Colour();
                if (! $w->contains($x))
                    $w->add($x);
                break;
            case 'image':
                $x = new Image();
                if (! $w->contains($x))
                    $w->add($x);
                break;
        }

        return $this->validators = $w->toArray();
    }

    /**
     * @param null|string $validators
     * @return Setting
     */
    public function setValidators(?array $validators): Setting
    {
        $this->validators = $validators ?: [];
        return $this;
    }

    /**
     * @var array|null
     */
    private $choices;

    /**
     * @return array|null
     */
    public function getChoices(): ?array
    {
        return $this->choices;
    }

    /**
     * @param array|null $choices
     * @return Setting
     */
    public function setChoices(?array $choices): Setting
    {
        $this->choices = $choices;
        return $this;
    }

    /**
     * setFlatChoices
     *
     * @param array|null $choices
     * @param bool $translate
     * @return Setting
     */
    public function setFlatChoices(?array $choices, $translate = true): Setting
    {
        $this->choices = [];
        foreach($choices as $name)
            if ($translate)
                $this->choices['setting.'.$this->getName().'.'.$name] = $name;
            else
                $this->choices[$name] = $name;
        return $this;
    }
}