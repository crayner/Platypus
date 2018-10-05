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
 * Time: 13:44
 */
namespace App\Organism;

use App\Entity\Setting;
use App\Manager\SettingManager;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Validator\Constraints\ColourValidator;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SettingCache
 * @package App\Organism
 */
class SettingCache
{
    /**
     * SettingCache constructor.
     * @param Setting|null $setting
     */
    public function __construct(?Setting $setting)
    {
        $this->setSetting($setting);
        if (! empty($setting))
            $this->setCacheTime(new \DateTime());
    }

    /**
     * @var Setting|null
     */
    private $setting;

    /**
     * @return Setting
     */
    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    /**
     * @param Setting $setting
     * @return SettingCache
     */
    public function setSetting(?Setting $setting): SettingCache
    {
        $this->setting = $setting;
        return $this;
    }
    /**
     * isValid
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if (empty($this->getSetting()))
            return false;

        return $this->isCacheTimeCurrent();
    }

    /**
     * @var \DateTime|null
     */
    private $cacheTime;

    /**
     * @return \DateTime|null
     */
    public function getCacheTime(): ?\DateTime
    {
        return $this->cacheTime;
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
            $value = $default;

        return $value;
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
        if (empty($this->value)) {
            if (empty($this->getSetting()) || empty($this->getSetting()->getSettingType()))
                return null;
            $method = 'get' . ucfirst($this->getSetting()->getSettingType()) . 'Value';
            $this->value = $this->getSetting()->getValue();
            if (method_exists($this, $method))
                $this->value = $this->$method();
            else {
                $this->value = $this->getSetting()->getValue();
                trigger_error(sprintf('There is no method %s in SettingCache', $method), E_USER_WARNING);
            }
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
        if (empty($this->getSetting()) || empty($this->getSetting()->getSettingType()))
            return $this;
        $method = 'set' . ucfirst($this->getSetting()->getSettingType()) . 'Value';
        if (method_exists($this, $method))
            $this->getSetting()->setValue($this->$method());
        else {
            trigger_error(sprintf('There is no method %s in SettingCache', $method), E_USER_WARNING);
        }

        return $this;
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
        if (! empty($this->getSetting()->getValidators()))
            $constraints = $this->getSetting()->getValidators();

        if (! empty($constraints)) {
            $errors = $validator->validate($this->getSetting()->getValue(), $constraints);
            if ($errors->count() > 0)
                return $errors;
        }

        $entityManager->persist($this->getSetting());
        $entityManager->flush();

        return true;
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
        return $this->setSetting($setting->setName($name));
    }

    /**
     * @var string|null
     */
    private $hideParent;

    /**
     * @return null|string
     */
    public function getHideParent(): ?string
    {
        return $this->hideParent;
    }

    /**
     * @param null|string $hideParent
     * @return SettingCache
     */
    public function setHideParent(?string $hideParent): SettingCache
    {
        $this->hideParent = $hideParent;
        return $this;
    }

    /**
     * __call
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->getSetting(), 'get' . ucfirst($name)))
            $name = 'get' . ucfirst($name);
        if (method_exists($this->getSetting(), 'is' . ucfirst($name)))
            $name = 'is' . ucfirst($name);

        return $this->getSetting()->$name();
    }

    /**
     * __set
     *
     * @param $name
     * @param $value
     * @return $this
     */
    public function __set($name, $value)
    {
        if (method_exists($this->getSetting(), 'set' . ucfirst($name)))
            $name = 'set' . ucfirst($name);

        $this->getSetting()->$name($value);
        return $this;
    }
    /**
     * getId
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getSetting()->getId();
    }

    /**
     * handleArguments
     *
     * @param array $arguments
     * @param SettingManager $settingManager
     * @return SettingCache
     */
    public function handleArguments(array $arguments, SettingManager $settingManager): SettingCache
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'parameter' => false,
                'default' => null,
                'hideParent' => null,
                'formAttr' => [],
                'placeholder' => false,
            ]
        );
        $arguments = $resolver->resolve($arguments);

        $this->setParameter($arguments['parameter'], $settingManager, $arguments['default']);
        $this->setHideParent($arguments['hideParent']);
        $this->setFormAttr($arguments['formAttr']);
        $this->setPlaceholder($arguments['placeholder']);

        return $this;
    }

    /**
     * @var string|boolean
     */
    private $placeholder;

    /**
     * @var array
     */
    private $formAttr;

    /**
     * @return array
     */
    public function getFormAttr(): array
    {
        return $this->formAttr;
    }

    /**
     * @param array $formAttr
     * @return SettingCache
     */
    public function setFormAttr(array $formAttr): SettingCache
    {
        $this->formAttr = $formAttr;
        return $this;
    }

    /**
     * @var boolean
     */
    private $parameter;

    /**
     * @return bool
     */
    public function isParameter(): bool
    {
        return $this->parameter;
    }

    /**
     * setParameter
     *
     * @param bool|null $parameter
     * @param SettingManager $settingManager
     * @param mixed|null $default
     * @return Setting
     */
    public function setParameter(?bool $parameter, SettingManager $settingManager, $default = null): SettingCache
    {
        $this->parameter = $parameter ? true : false ;

        if ($this->parameter)
            $this->setValue($settingManager->getParameter(str_replace(':', '.', $this->getSetting()->getName()), $default));

        return $this;
    }

    /**
     * @return bool|string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param bool|string $placeholder
     * @return SettingCache
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * getSystemValue
     *
     * @return mixed
     */
    private function getSystemValue()
    {
        return $this->value;
    }

    /**
     * setSystemValue
     *
     * @return mixed
     */
    private function setSystemValue()
    {
        return $this->value;
    }

    /**
     * getNumberValue
     *
     * @return mixed
     */
    private function getNumberValue()
    {
        return $this->value = floatval($this->value);
    }

    /**
     * setNumberValue
     *
     * @return mixed
     */
    private function setNumberValue()
    {
        return $this->value = floatval($this->value);
    }

    /**
     * getIntegerValue
     *
     * @return mixed
     */
    private function getIntegerValue()
    {
        return $this->value = intval($this->value);
    }

    /**
     * setIntegerValue
     *
     * @return mixed
     */
    private function setIntegerValue()
    {
        return $this->value = intval($this->value);
    }

    /**
     * getBooleanValue
     *
     * @return mixed
     */
    private function getBooleanValue()
    {
        return $this->value ? true : false ;
    }

    /**
     * setBooleanValue
     *
     * @return mixed
     */
    private function setBooleanValue()
    {
        return $this->value = $this->value ? true : false ;
    }

    /**
     * getArrayValue
     *
     * @return array
     */
    private function getArrayValue(): array
    {
        if (is_array($this->value))
            return $this->value;
        if (empty(trim($this->value)))
            return [];
        $w = Yaml::parse($this->value);
        if (is_array($w))
            return $w;

        try {
            $w = unserialize(trim($this->value));
        } catch (\ErrorException $e) {
            $w = [];
        }
        return $w;
    }

    /**
     * setArrayValue
     *
     * @return string
     */
    private function setArrayValue()
    {
        return $this->value = Yaml::dump($this->value ?: []);
    }

    /**
     * getColourValue
     *
     * @return mixed|string
     */
    private function getColourValue()
    {
        if (ColourValidator::isColour($this->value))
            return $this->value;
        return '#000000';
    }

    /**
     * setColourValue
     *
     * @return mixed|string
     */
    private function setColourValue()
    {
        if (ColourValidator::isColour($this->value))
            return $this->value;
        return $this->value = '#000000';
    }

    /**
     * getMultiEnumValue
     *
     * @return mixed
     */
    private function getMultiEnumValue()
    {
        try {
            return unserialize($this->value);
        } catch (\ErrorException $e) {
            return [];
        }
    }

    /**
     * setMultiEnumValue
     *
     * @return string
     */
    private function setMultiEnumValue()
    {
        return $this->value = serialize(is_array($this->value) ? $this->value : []);
    }

    /**
     * getImageValue
     *
     * @return mixed
     */
    private function getImageValue()
    {
        return $this->value;
    }

    /**
     * setImageValue
     *
     * @return mixed
     */
    private function setImageValue()
    {
        return $this->value;
    }

    /**
     * getStringValue
     *
     * @return mixed
     */
    private function getStringValue()
    {
        return $this->value;
    }

    /**
     * setStringValue
     *
     * @return mixed
     */
    private function setStringValue()
    {
        return $this->value;
    }

    /**
     * getHtmlValue
     *
     * @return mixed
     */
    private function getHtmlValue()
    {
        return $this->value;
    }

    /**
     * setHtmlValue
     *
     * @return mixed
     */
    private function setHtmlValue()
    {
        return $this->value;
    }

    /**
     * getEnumValue
     *
     * @return mixed
     */
    private function getEnumValue()
    {
        return $this->value;
    }

    /**
     * setEnumValue
     *
     * @return mixed
     */
    private function setEnumValue()
    {
        return $this->value;
    }

    /**
     * getTextValue
     *
     * @return mixed
     */
    private function getTextValue()
    {
        return $this->value;
    }

    /**
     * setTextValue
     *
     * @return mixed
     */
    private function setTextValue()
    {
        return $this->value;
    }

    /**
     * getUrlValue
     *
     * @return mixed
     */
    private function getUrlValue()
    {
        return $this->value;
    }

    /**
     * setUrlValue
     *
     * @return mixed
     */
    private function setUrlValue()
    {
        return $this->value;
    }

    /**
     * getEmailValue
     *
     * @return mixed
     */
    private function getEmailValue()
    {
        return $this->value;
    }

    /**
     * setEmailValue
     *
     * @return mixed
     */
    private function setEmailValue()
    {
        return $this->value;
    }

    /**
     * getCurrencyValue
     *
     * @return mixed
     */
    private function getCurrencyValue()
    {
        return $this->value;
    }

    /**
     * setCurrencyValue
     *
     * @return mixed
     */
    private function setCurrencyValue()
    {
        return $this->value;
    }

    /**
     * getTwigValue
     *
     * @return mixed
     */
    private function getTwigValue()
    {
        return $this->value;
    }

    /**
     * setTwigValue
     *
     * @return mixed
     */
    private function setTwigValue()
    {
        return $this->value;
    }
}