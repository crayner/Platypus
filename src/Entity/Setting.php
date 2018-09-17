<?php
namespace App\Entity;

use App\Util\PhotoHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Hillrange\Form\Validator\Colour;
use Hillrange\Form\Validator\Integer;
use Hillrange\Security\Util\UserTrackInterface;
use Hillrange\Security\Util\UserTrackTrait;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Setting
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
        'boolean',
        'choice',
        'colour',
        'currency',
        'date',
        'email',
        'file',
        'html',
        'image',
        'integer',
        'multiChoice',
        'number',
        'string',
        'system',
        'text',
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
        if ($this->getSettingType() === 'image' && $value !== $this->getValue())
            PhotoHelper::deletePhotoFile($this->getValue());

        $this->value = $value;
        return $this;
    }

    /**
     * @var array|null
     */
    private $choice;

    /**
     * getChoice
     *
     * @return array
     */
    public function getChoice(): array
    {
        return $this->choice = empty($this->choice) ? [] : is_array($this->choice) ? $this->choice : [] ;
    }

    /**
     * setChoice
     *
     * @param array|null $choice
     * @return Setting
     */
    public function setChoice(?array $choice): Setting
    {
        $this->choice = $choice ?: [];
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
     * add Validator
     *
     * @param null|Constraint $constraint
     * @return Setting
     */
    public function addValidator(?Constraint $constraint): Setting
    {
        if (empty($constraint) || in_array($constraint, $this->getValidators()))
            return $this;

        $this->validators[] = $constraint;

        return $this;
    }

    /**
     * @var string|null
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
     * @return Setting
     */
    public function setRole(?string $role): Setting
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return Setting
     */
    public function setDefaultValue($defaultValue): Setting
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * @var bool
     */
    private $valid = true;

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @param bool $valid
     * @return Setting
     */
    public function setValid(bool $valid): Setting
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * @var string|null
     */
    private $translateChoice;

    /**
     * @return null|boolean|string
     */
    public function getTranslateChoice()
    {
        return $this->translateChoice;
    }

    /**
     * @param null|boolean|string $translateChoice
     * @return Setting
     */
    public function setTranslateChoice($translateChoice): Setting
    {
        $this->translateChoice = $translateChoice;
        return $this;
    }

    /**
     * __toArray
     *
     * @return array
     */
    public function __toArray()
    {
        return get_object_vars($this);
    }
}
