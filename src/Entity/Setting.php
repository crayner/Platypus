<?php
namespace App\Entity;

use App\Core\Organism\SettingCache;
use Hillrange\Security\Util\UserTrackInterface;
use Hillrange\Security\Util\UserTrackTrait;
use Symfony\Component\Validator\Constraint;

/**
 * Setting
 */
class Setting implements UserTrackInterface
{
	use UserTrackTrait;

    /**
     * @var int|null
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
     * @return Setting
     */
    public function setId(?int $id): Setting
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $type;

    /**
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     * @return Setting
     */
    public function setType(?string $type): Setting
    {
        $this->type = $type;
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
        $this->value = $value;
        return $this;
    }

    /**
     * @var string|null
     */
    private $choice;

    /**
     * @return null|string
     */
    public function getChoice(): ?string
    {
        return $this->choice;
    }

    /**
     * @param null|string $choice
     * @return Setting
     */
    public function setChoice(?string $choice): Setting
    {
        $this->choice = $choice;
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
        return $this->validators ?: [];
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
     * @return null|string
     */
    public function getTranslateChoice(): ?string
    {
        return $this->translateChoice;
    }

    /**
     * @param null|string $translateChoice
     * @return Setting
     */
    public function setTranslateChoice(?string $translateChoice): Setting
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
