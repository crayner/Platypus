<?php
namespace App\Entity;

/**
 * Phone
 */
class Phone
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $phoneType;

	/**
	 * @var string
	 */
	private $phoneNumber;

	/**
	 * @var string
	 */
	private $countryCode;

    /**
     * @return array
     */
    public static function getPhoneTypeList(): array
    {
        return self::$phoneTypeList;
    }

    /**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

    /**
     * @param int|null $id
     * @return Phone
     */
    public function setId(?int $id): Phone
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var array
     */
    private static $phoneTypeList = [
        '',
        'home',
        'mobile',
        'work',
        'fax',
    ];
	/**
	 * Get phoneType
	 *
	 * @return string
	 */
	public function getPhoneType(): ?string
	{
		return $this->phoneType = in_array($this->phoneType, self::$phoneTypeList) ? $this->phoneType : '';
	}

	/**
	 * Set phoneType
	 *
	 * @param string $phoneType
	 *
	 * @return Phone
	 */
	public function setPhoneType(?string $phoneType): Phone
	{
		$this->phoneType = in_array($phoneType, self::$phoneTypeList) ? $phoneType : '';;

		return $this;
	}

	/**
	 * Get phoneNumber
	 *
	 * @return string
	 */
	public function getPhoneNumber(): ?string
	{
		return $this->phoneNumber;
	}

	/**
	 * Set phoneNumber
	 *
	 * @param string $phoneNumber
	 *
	 * @return Phone
	 */
	public function setPhoneNumber($phoneNumber): Phone
	{
		$this->phoneNumber = $phoneNumber;

		return $this;
	}

	/**
	 * Get countryCode
	 *
	 * @return string
	 */
	public function getCountryCode(): ?string
	{
		return strtoupper($this->countryCode);
	}

	/**
	 * Set countryCode
	 *
	 * @param string $countryCode
	 *
	 * @return Phone
	 */
	public function setCountryCode(?string $countryCode): Phone
	{
		$this->countryCode = strtoupper($countryCode);

		return $this;
	}

    /**
     * toArray
     *
     * @return array
     */
	public function toArray(): array
    {
        $phone = [];
        $phone['id'] = $this->getId();
        $phone['country'] = $this->getCountryCode();
        $phone['phone'] = $this->getPhoneNumber();
        $phone['type'] = $this->getPhoneType();
        return $phone;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return '('.$this->getId().') ' . $this->getCountryCode() . ' ' . $this->getPhoneNumber();
    }
}
