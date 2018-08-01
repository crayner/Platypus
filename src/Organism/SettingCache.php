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
 * Date: 26/05/2018
 * Time: 08:05
 */
namespace App\Organism;

use App\Entity\Setting;
use App\Manager\SettingManager;
use App\Repository\SettingRepository;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class SettingCache
{
    public function __construct(?Setting $setting)
    {
        $this->setSetting($setting);
    }

    /**
     * @var Setting
     */
    private $setting;


    /**
     * @var string
     */
    private $hideParent;

    /**
     * @var array
     */
    private $chainOptions;

    /**
     * @var array
     */
    private $entityOptions;

    /**
     * @return null|string
     */
    public function getHideParent(): ?string
    {
        return $this->hideParent;
    }

    /**
     * @param string $hideParent
     * @return SettingCache
     */
    public function setHideParent(string $hideParent): SettingCache
    {
        $this->hideParent = $hideParent;
        return $this;
    }

    /**
     * @return array
     */
    public function getChainOptions(): array
    {
        return $this->chainOptions;
    }

    /**
     * @param array $chainOptions
     * @return SettingCache
     */
    public function setChainOptions(array $chainOptions): SettingCache
    {
        $this->chainOptions = $chainOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getEntityOptions(): array
    {
        return $this->entityOptions ?: [];
    }

    /**
     * @param array $entityOptions
     * @return SettingCache
     */
    public function setEntityOptions(array $entityOptions): SettingCache
    {
        $this->entityOptions = $entityOptions;
        return $this;
    }

    /**
     * @var boolean
     */
    private $parameter = false;

    /**
     * @return bool
     */
    public function isParameter(): bool
    {
        return $this->parameter ? true : false ;
    }

    /**
     * @param bool $parameter
     * @return SettingCache
     */
    public function setParameter(bool $parameter, SettingManager $settingManager, $default = null): SettingCache
    {
        $this->parameter = $parameter ? true : false ;
        if ($this->parameter)
            $this->setValue($settingManager->getParameter(str_replace(':', '.', $this->getName()), $default));

        return $this;
    }

    /**
     * @return Setting|null
     */
    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    /**
     * @param Setting|null $setting
     * @return SettingCache
     */
    public function setSetting(?Setting $setting): SettingCache
    {
        $this->setting = $setting;
        return $this;
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return strtolower($this->name);
    }

    /**
     * @param string|null $name
     * @return SettingCache
     */
    public function setName(?string $name = null): SettingCache
    {
        $this->name = strtolower($name ?: $this->getSetting()->getName());
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $cacheTime;

    /**
     * @return bool
     */
    public function isCacheTimeCurrent(): bool
    {
        if (empty($this->cacheTime))
            return false;

        if ($this->cacheTime->getTimestamp() > strtotime('-20 minutes'))
            return true;

        $this->setCacheTime(null);

        return false;
    }

    /**
     * @param \DateTime $cacheTime
     * @return SettingCache
     */
    public function setCacheTime(?\DateTime $cacheTime): SettingCache
    {
        $this->cacheTime = $cacheTime;
        return $this;
    }

    /**
     * @var mixed
     */
    private $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        if ($this->value === $this)
            $this->value = null;
        if (empty($this->value) && $this->isBaseSetting()) {
            if (empty($this->getSetting()) || empty($this->getType()))
                return null;
            $method = 'get' . ucfirst($this->getType()) . 'Value';
            if (method_exists($this, $method))
                $this->value = $this->$method();
            else
                $this->value = $this->getTextValue();
        }
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return SettingCache
     */
    public function setValue($value): SettingCache
    {
        $this->value = $value;
        if ($this->isBaseSetting()) {
            if (empty($this->getSetting()) || empty($this->getType()))
                return $this;
            $method = 'set' . ucfirst($this->getType()) . 'Value';
            if (method_exists($this, $method))
                $this->value = $this->$method();
            else
                $this->getSetting()->setValue($this->value);
        }
        return $this;
    }

    /**
     * @var mixed
     */
    private $defaultValue;

    /**
     * @var string|null
     */
    private $parent;

    /**
     * @return null|string
     */
    public function getParent(): ?string
    {
        return $this->parent;
    }

    /**
     * @param null|string $parent
     * @return SettingCache
     */
    public function setParent(?string $parent): SettingCache
    {
        $this->parent = $parent;
        if (! empty($parent))
            $this->setBaseSetting(false);
        return $this;
    }

    /**
     * @var string
     */
    private $parentKey;

    /**
     * @return string
     */
    public function getParentKey(): string
    {
        return $this->parentKey;
    }

    /**
     * @param string $parentKey
     * @return SettingCache
     */
    public function setParentKey(string $parentKey): SettingCache
    {
        $this->parentKey = $parentKey;
        return $this;
    }

    /**
     * getDefaultValue
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        if (empty($this->defaultValue) && $this->isBaseSetting()) {
            $method = 'getDefault' . ucfirst($this->getSetting()->getType()) . 'Value';
            if (method_exists($this, $method))
                $this->defaultValue = $this->$method();
            else
                $this->defaultValue = $this->getDefaultTextValue();
        }
        return $this->defaultValue;
    }

    /**
     * @param mixed $defaultValue
     * @return SettingCache
     */
    public function setDefaultValue($defaultValue): SettingCache
    {
        $this->defaultValue = $defaultValue;
        if ($this->isBaseSetting()) {
            if (empty($this->getSetting()) || empty($this->getSetting()->getType()))
                return $this;
            $method = 'setDefault' . ucfirst($this->getSetting()->getType()) . 'Value';
            if (method_exists($this, $method))
                return $this->$method();
            else
                $this->getSetting()->setDefaultValue($defaultValue);
        }
        return $this;
    }

    /**
     * getArrayValue
     *
     * @return array
     */
    private function getArrayValue(): array
    {
        return self::convertDatabaseToArray($this->getSetting()->getValue());
    }

    /**
     * settArrayValue
     *
     * @return SettingCache
     */
    private function setArrayValue(): SettingCache
    {
        $this->getSetting()->setValue(Yaml::dump($this->value));
        return $this;
    }

    /**
     * getMultiChoiceValue
     *
     * @return array
     */
    private function getMultiChoiceValue(): array
    {
        $result = json_decode($this->getSetting()->getValue()) ?: [];
        return is_array($result) ? $result : [];
    }

    /**
     * setMultiChoiceValue
     *
     * @return SettingCache
     */
    private function setMultiChoiceValue(): SettingCache
    {
        $this->getSetting()->setValue(json_encode(is_array($this->value) ? $this->value : []));
        return $this;
    }

    /**
     * getTextValue
     *
     * @return null|string
     */
    private function getTextValue(): ?string
    {
        return $this->getSetting()->getValue();
    }

    /**
     * setTextValue
     *
     * @return SettingCache
     */
    private function setTextValue(): SettingCache
    {
        $this->getSetting()->setValue($this->value);
        return $this;
    }

    /**
     * getStringValue
     *
     * @return null|string
     */
    private function getStringValue(): ?string
    {
        return mb_substr($this->getSetting()->getValue() ?: '', 0, 255);
    }

    /**
     * setStringValue
     *
     * @return SettingCache
     */
    private function setStringValue(): SettingCache
    {
        $this->getSetting()->setValue(mb_substr($this->value, 0, 255));
        return $this;
    }

    /**
     * getDateTimeValue
     *
     * @param null $default
     * @return \DateTime|null
     */
    private function getDateTimeValue($default = null): ?\DateTime
    {
        if ($this->value)
            return $this->value;
        return $this->value = unserialize($this->getSetting()->getValue() ?: $this->getDefaultValue($default));
    }

    /**
     * setDateTimeValue
     *
     * @return SettingCache
     */
    private function setDateTimeValue(): SettingCache
    {
        if ($this->value)
            $this->getSetting()->setValue(serialize($this->value));
        else
            $this->getSetting()->setValue(null);
        return $this;
    }

    /**
     * getTimeValue
     *
     * @param null $default
     * @return \DateTime|null
     */
    private function getTimeValue($default = null): ?\DateTime
    {
        return $this->getDateTimeValue($default);
    }

    /**
     * setTimeValue
     *
     * @return SettingCache
     */
    private function setTimeValue(): SettingCache
    {
        return $this->setDateTimeValue();
    }

    /**
     * getBooleanValue
     *
     * @return bool
     */
    private function getBooleanValue(): bool
    {

        return $this->getSetting()->getValue() ? true : false;
    }

    /**
     * setBooleanValue
     *
     * @return SettingCache
     */
    private function setBooleanValue(): SettingCache
    {
        $this->value = $this->value ? true : false;
        $this->getSetting()->setValue($this->value);
        return $this;
    }

    /**
     * getIntegerValue
     *
     * @return string
     */
    private function getIntegerValue(): string
    {
        return strval(intval($this->getSetting()->getValue()));
    }

    /**
     * setIntegerValue
     *
     * @return SettingCache
     */
    private function setIntegerValue(): SettingCache
    {
        $this->value = strval(intval($this->value));
        $this->getSetting()->setValue($this->value);
        return $this;
    }

    /**
     * @var bool
     */
    private $baseSetting = true;

    /**
     * @return bool
     */
    public function isBaseSetting(): bool
    {
        return $this->baseSetting;
    }

    /**
     * @param bool $baseSetting
     * @return SettingCache
     */
    public function setBaseSetting(bool $baseSetting): SettingCache
    {
        $this->baseSetting = $baseSetting;
        return $this;
    }

    /**
     * convertDateTimeToDataBase
     *
     * @param $value
     * @return null|string
     */
    public static function convertDateTimeToDataBase($value): ?string
    {
        if (empty($value))
            return null;
        if ($value Instanceof \DateTime)
            return serialize($value);
        return $value;
    }

    /**
     * convertDateTimeFromDataBase
     *
     * @param $value
     * @return \DateTime|null
     */
    public static function convertDatabaseToDateTime($value): ?\DateTime
    {
        if (empty($value) || $value instanceof \DateTime)
            return $value;

        try {
            return unserialize($value);
        } catch (\ErrorException $e)
        {
            return null;
        }
    }

    /**
     * convertArrayToDatabase
     *
     * @param $value
     * @return null|string
     */
    public static function convertArrayToDatabase($value): ?string
    {
        if (empty($value))
            return null;
        if (is_array($value))
            return Yaml::dump($value);
        return $value;
    }

    /**
     * convertDatabaseToArray
     *
     * @param $value
     * @return array
     */
    public static function convertDatabaseToArray($value): array
    {
        if (empty($value))
            return [];
        if (is_array($value))
            return $value;

        try {
            $x = Yaml::parse($value);
        } catch (ParseException $e)
        {
            return [];
        }
        return is_array($x) ? $x : [];
    }

    /**
     * getDefaultArrayValue
     *
     * @return array|null
     */
    private function getDefaultArrayValue(): ?array
    {
        return self::convertDatabaseToArray($this->getSetting()->getDefaultValue());
    }

    /**
     * getDefaultMultiChoiceValue
     *
     * @return array|null
     */
    private function getDefaultMultiChoiceValue(): ?array
    {
        return json_decode($this->getSetting()->getValue()) ?: [];
    }

    /**
     * getDefaultTextValue
     *
     * @return null|string
     */
    private function getDefaultTextValue(): ?string
    {
        $value = $this->getSetting()->getDefaultValue();
        if (is_null($value) || is_string($value))
            return $value;
        return null;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * getType
     *
     * @return string
     */
    public function getType(): ?string
    {
        if ($this->isBaseSetting())
            return $this->getSetting()->getType();
        return $this->type;
    }

    /**
     * setType
     *
     * @param $type
     * @return SettingCache
     */
    public function setType($type): SettingCache
    {
        $this->type = $type;
        if (! empty($this->getSetting()))
            $this->getSetting()->setType($type);
        return $this;
    }

    /**
     * convertImportValues
     *
     * @return SettingCache
     */
    public function convertImportValues(): SettingCache
    {
        switch ($this->getType()){
            case 'time':
                $this->value = $this->value ? new \DateTime('1970-01-01 ' . $this->value) : null ;
                $this->defaultValue = $this->defaultValue ? new \DateTime('1970-01-01 ' . $this->defaultValue) : null ;
                break;
            default:
        }
        $this->setValue($this->value);
        $this->setDefaultValue($this->defaultValue);
        return $this;
    }

    /**
     * importSetting
     *
     * @param array $values
     * @param EntityManagerInterface $entityManager
     * @return bool
     * @throws TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function importSetting(array $values, EntityManagerInterface $entityManager): bool
    {
        $this->findOneByName($values['name'], $entityManager);
        $this->setting = $this->getSetting() instanceof Setting ? $this->getSetting() : new Setting();
        foreach ($values as $field => $value) {
            $func = 'set' . ucfirst($field);
            if ($field === 'value')
                $this->value = $value;
            elseif ($field === 'defaultValue')
                $this->defaultValue = $value;
            elseif ($field === 'validators')
                $this->validators = $this->convertValidators($value);
            else
                $this->getSetting()->$func($value);
        }
        $this->convertImportValues();
        $entityManager->persist($this->getSetting());
        $entityManager->flush();

        return true;
    }

    /**
     * convertValidators
     *
     * @param $value
     * @return array
     */
    public function convertValidators($value): array
    {
        if (empty($value))
            return [];

        if (! is_array($value))
            $value = [$value];

        $results = [];

        foreach($value as $validator)
            if (class_exists($validator))
                $results[] = new $validator();
            else
                trigger_error('The validator ' .$validator . ' is not available', E_USER_ERROR);

        return $results;
    }

    /**
     * findOneByName
     *
     * @param string $name
     * @param EntityManagerInterface $entityManager
     * @return SettingCache|null
     * @throws TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function findOneByName(string $name, EntityManagerInterface $entityManager): ?SettingCache
    {
        try {
            $this->setting = $this->getSettingRepository($entityManager)->findOneByName(strtolower($name));
            $this->setCacheTime(new \DateTime('now'));
            $this->setName($name);
        } catch (TableNotFoundException $e) {
            if (in_array($e->getErrorCode(), ['1146', '1045']))
                $this->setting = null;
            else
                throw $e;
        } catch (ConnectionException $e) {
            $this->setting = null;
        }
        return $this;
    }

    /**
     * getSettingRepository
     *
     * @param EntityManagerInterface $entityManager
     * @return SettingRepository
     */
    private function getSettingRepository(EntityManagerInterface $entityManager): SettingRepository
    {
        return $entityManager->getRepository(Setting::class);
    }

    /**
     * isValid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (! $this->isBaseSetting() && ! empty($this->getName() && $this->isCacheTimeCurrent())) {
            if (is_array($this->getValue()) && !$this->getType() === 'System')
                return false;
            return true;
        }

        if ($this->isBaseSetting() && ! $this->getSetting() instanceof Setting)
            return false;

        if (empty($this->getName()) || empty($this->getSetting()) || empty($this->getSetting()->getId()))
            return false;

        return $this->isCacheTimeCurrent();
    }

    /**
     * setDefaultTextValue
     *
     * @return SettingCache
     */
    private function setDefaultTextValue(): SettingCache
    {
        $this->getSetting()->setDefaultValue($this->defaultValue);
        return $this;
    }

    /**
     * setDefaultArrayValue
     *
     * @return SettingCache
     */
    private function setDefaultArrayValue(): SettingCache
    {
        $this->getSetting()->setDefaultValue(Yaml::dump($this->defaultValue));
        return $this;
    }

    /**
     * setDefaultMultiChoiceValue
     *
     * @return SettingCache
     */
    private function setDefaultMultiChoiceValue(): SettingCache
    {
        $this->getSetting()->setDefaultValue(json_encode($this->defaultValue ?: []));
        return $this;
    }

    /**
     * setDefaultTimeValue
     *
     * @return SettingCache
     */
    private function setDefaultTimeValue(): SettingCache
    {
        return $this->setDefaultDateTimeValue();
    }

    /**
     * setDefaultDateTimeValue
     *
     * @return SettingCache
     */
    private function setDefaultDateTimeValue(): SettingCache
    {
        if ($this->defaultValue)
            $this->getSetting()->setDefaultValue(serialize($this->defaultValue));
        else
            $this->getSetting()->setDefaultValue(null);
        return $this;
    }

    /**
     * getDefaultIntegerValue
     *
     * @return string
     */
    private function getDefaultIntegerValue(): string
    {

        return strval(intval($this->getSetting()->getDefaultValue()));
    }

    /**
     * setDefaultIntegerValue
     *
     * @return SettingCache
     */
    private function setDefaultIntegerValue(): SettingCache
    {
        $this->defaultValue = strval(intval($this->defaultValue));
        $this->getSetting()->setDefaultValue($this->defaultValue);
        return $this;
    }

    /**
     * getDefaultBooleanValue
     *
     * @return bool
     */
    private function getDefaultBooleanValue(): bool
    {
        return $this->getSetting()->getDefaultValue() ? true : false;
    }

    /**
     * setDefaultBooleanValue
     *
     * @return SettingCache
     */
    private function setDefaultBooleanValue(): SettingCache
    {
        $this->defaultValue = $this->defaultValue ? true : false;
        $this->getSetting()->setDefaultValue($this->defaultValue);
        return $this;
    }

    /**
     * getFinalValue
     *
     * @param $default
     * @return mixed|null
     */
    public function getFinalValue($default)
    {
        $value = null;
        $value = $this->getValue();

        if (empty($value))
            $value = $this->getDefaultValue();
        if (empty($value))
            $value = $default;

        return $value;
    }

    /**
     * writeSetting
     *
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param array $constraints
     * @return boolean|ConstraintViolationListInterface
     */
    public function writeSetting(EntityManagerInterface $entityManager, ValidatorInterface $validator, array $constraints)
    {
        if (! empty($this->getValidators()))
            $constraints = $this->getValidators();

        if (! empty($constraints)) {
            $errors = $validator->validate($this->getSetting()->getValue(), $constraints);
            $errors->addAll($validator->validate($this->getSetting()->getDefaultValue(), $constraints));
            if ($errors->count() > 0)
                return $errors;
        }

        if ($this->isBaseSetting()) {
            $entityManager->persist($this->getSetting());
            $entityManager->flush();
        }

        return true;
    }

    /**
     * getTranslateChoice
     *
     * @return null|string
     */
    public function getTranslateChoice(): ?string
    {
        if ($this->getSetting())
            return $this->getSetting()->getTranslateChoice();
        return null;
    }

    /**
     * getId
     *
     * @return null|integer
     */
    public function getId(): ?int
    {
        if ($this->getSetting())
            return $this->getSetting()->getId();
        return null;
    }

    /**
     * getValidators
     *
     * @return null|array
     */
    public function getValidators(): ?array
    {
        if ($this->getSetting())
            return $this->getSetting()->getValidators();
        return null;
    }

    /**
     * setValidators
     *
     * @param $value
     * @return SettingCache
     */
    public function setValidators($value): SettingCache
    {
        if ($this->getSetting())
            $this->getSetting()->setValidators($value);
        return $this;
    }

    /**
     * getDisplayName
     *
     * @return null|string
     */
    public function getDisplayName(): ?string
    {
        if ($this->getSetting())
            return $this->getSetting()->getDisplayName();
        return null;
    }

    /**
     * __set
     *
     * @param string $name
     * @param $value
     * @return SettingCache
     */
    public function __set(string $name, $value): SettingCache
    {
        $method = 'set' . ucfirst($name);
        if (method_exists($this, $method))
            return $this->$method($value);

        if ($this->getSetting())
        {
            $this->getSetting()->$method($value);
            return $this;
        }

        trigger_error('The setting field "'.$name.'"" does not seem to exist.', E_USER_ERROR);
    }


    /**
     * __get
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method))
            return $this->$method();

        if ($this->getSetting())
        {
            return $this->getSetting()->$method();
        }

        trigger_error('The setting field "'.$name.'"" does not seem to exist.', E_USER_ERROR);
    }
    /**
     * getDescription
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        if ($this->getSetting())
            return $this->getSetting()->getDescription();
        return null;
    }

    /**
     * createOneByName
     *
     * @param string $name
     * @return SettingCache
     */
    public function createOneByName(string $name): SettingCache
    {
        $setting = new Setting();
        $name = strtolower($name);
        return $this->setSetting($setting->setName($name))
            ->setName($name);
    }
}