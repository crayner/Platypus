<?php
namespace App\Entity;

/**
 * Address
 */
class Address
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $propertyName;

	/**
	 * @var string
	 */
	private $streetName;

	/**
	 * @var string
	 */
	private $buildingType;

    /**
     * @var
     */
	private static $buildingTypeList = [
        '',
        'flat',
        'unit',
        'apt',
        'thse',
    ];

	/**
	 * @var string
	 */
	private $buildingNumber;

	/**
	 * @var string
	 */
	private $streetNumber;

	/**
	 * @var Locality
	 */
	private $locality;

    /**
     * @return mixed
     */
    public static function getBuildingTypeList()
    {
        return self::$buildingTypeList;
    }

    /**
     * @param mixed $buildingTypeList
     */
    public static function setBuildingTypeList($buildingTypeList): void
    {
        self::$buildingTypeList = $buildingTypeList;
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
	 * Get propertyName
	 *
	 * @return string
	 */
	public function getPropertyName()
	{
		return empty($this->propertyName) ? "" : $this->propertyName;
	}

	/**
	 * Set propertyName
	 *
	 * @param string $propertyName
	 *
	 * @return Address
	 */
	public function setPropertyName($propertyName)
	{
		$this->propertyName = empty($propertyName) || is_null($propertyName) ? "" : $propertyName;

		return $this;
	}

	/**
	 * Get streetName
	 *
	 * @return string
	 */
	public function getStreetName()
	{
		return $this->streetName;
	}

	/**
	 * Set streetName
	 *
	 * @param string $streetName
	 *
	 * @return Address
	 */
	public function setStreetName($streetName)
	{
		$this->streetName = $streetName;

		return $this;
	}

	/**
	 * Get buildingType
	 *
	 * @return string
	 */
	public function getBuildingType()
	{
		if (empty($this->buildingType))
			$this->buildingType = '';

		return empty($this->buildingType) ? "" : $this->buildingType;
	}

	/**
	 * Set buildingType
	 *
	 * @param string $buildingType
	 *
	 * @return Address
	 */
	public function setBuildingType($buildingType)
	{

		$this->buildingType = empty($buildingType) ? '' : $buildingType;

		return $this;
	}

	/**
	 * Get buildingNumber
	 *
	 * @return string
	 */
	public function getBuildingNumber()
	{
		return empty($this->buildingNumber) ? "" : $this->buildingNumber;
	}

	/**
	 * Set buildingNumber
	 *
	 * @param string $buildingNumber
	 *
	 * @return Address
	 */
	public function setBuildingNumber($buildingNumber)
	{
		$this->buildingNumber = empty($buildingNumber) || is_null($buildingNumber) ? '' : $buildingNumber;

		return $this;
	}

	/**
	 * Get streetNumber
	 *
	 * @return string
	 */
	public function getStreetNumber()
	{
		return empty($this->streetNumber) ? "" : $this->streetNumber;
	}

	/**
	 * Set streetNumber
	 *
	 * @param string $streetNumber
	 *
	 * @return Address
	 */
	public function setStreetNumber($streetNumber)
	{
		$this->streetNumber = empty($streetNumber) ? '' : $streetNumber;

		return $this;
	}

	/**
	 * Get locality
	 *
	 * @return Locality
	 */
	public function getLocality()
	{
		return $this->locality;
	}

	/**
	 * Set locality
	 *
	 * @param Locality $locality
	 *
	 * @return Address
	 */
	public function setLocality(Locality $locality = null)
	{
		$this->locality = $locality;

		return $this;
	}

    /**
     * getSingleLineAddress
     *
     * @return string
     */
    public function getSingleLineAddress()
    {
        return trim($this->getPropertyName() . ' ' . $this->getBuildingNumber() . '/'
            . $this->getStreetNumber() . ' ' . $this->getStreetName() . ' ' . $this->getLocality()->getName()
            . ' ' . $this->getLocality()->getTerritory() . ' ' . $this->getPostCode() . $this->getLocality()->getPostCode(), ' /');

    }

    /**
     * Address constructor.
     */
    public function __construct()
    {
        $this->setLocality(new Locality());
    }

    /**
     * to String
     *
     * @return mixed
     */
    public function __toString(): string
    {
        return $this->getSingleLineAddress();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        if (empty(trim($this->__toString())))
            return true;

        return false;
    }

    /**
     * @var string|null
     */
    private $postCode;

    /**
     * @return null|string
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @param null|string $postCode
     * @return Address
     */
    public function setPostCode(?string $postCode): Address
    {
        $this->postCode = $postCode;
        return $this;
    }
}
