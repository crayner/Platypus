<?php
namespace App\Entity;

/**
 * Locality
 */
class Locality
{
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $territory;

	/**
	 * @var string
	 */
	private $postCode;

	/**
	 * @var string
	 */
	private $country;


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
	 * Set Id
	 *
	 * @param integer $id
	 *
	 * @return Locality
	 */
	public function setId($id): Locality
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Get locality
	 *
	 * @return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Set locality
	 *
	 * @param string $locality
	 *
	 * @return Locality
	 */
	public function setName(?string $name): Locality
	{
		$this->name = strtoupper($name);

		return $this;
	}

	/**
	 * Get territory
	 *
	 * @return string
	 */
	public function getTerritory(): ?string
	{
		return $this->territory;
	}

	/**
	 * Set territory
	 *
	 * @param string $territory
	 *
	 * @return Locality
	 */
	public function setTerritory($territory): Locality
	{
		$this->territory = $territory;

		return $this;
	}

	/**
	 * Get postCode
	 *
	 * @return string
	 */
	public function getPostCode(): ?string
	{
		return $this->postCode;
	}

	/**
	 * Set postCode
	 *
	 * @param string $postCode
	 *
	 * @return Locality
	 */
	public function setPostCode($postCode): Locality
	{
		$this->postCode = $postCode;

		return $this;
	}

	/**
	 * Get country
	 *
	 * @return string
	 */
	public function getCountry(): ?string
	{
		return $this->country;
	}

	/**
	 * Set country
	 *
	 * @param string $country
	 *
	 * @return Locality
	 */
	public function setCountry($country): Locality
	{
		$this->country = $country;

		return $this;
	}

    /**
     * toArray
     *
     * @return array
     */
	public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'territory' => $this->getTerritory(),
            'postCode' => $this->getPostCode(),
            'country' => $this->getCountry(),
            'id' => $this->getId(),
        ];
    }
}
