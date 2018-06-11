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
 * Time: 16:39
 */
namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class Activity
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
     * @return Activity
     */
    public function setId(?int $id): Activity
    {
        $this->id = $id;
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
     * @return Activity
     */
    public function setActive(bool $active): Activity
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @var bool
     */
    private $registration;

    /**
     * @return bool
     */
    public function isRegistration(): bool
    {
        return $this->registration;
    }

    /**
     * @param bool $registration
     * @return Activity
     */
    public function setRegistration(bool $registration): Activity
    {
        $this->registration = $registration;
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
     * @return Activity
     */
    public function setName(?string $name): Activity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @var array
     */
    public static $providerList = [
        'school',
        'external',
    ];

    /**
     * @var string
     */
    private $provider;

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider ?: 'school';
    }

    /**
     * @param string $provider
     * @return Activity
     */
    public function setProvider(string $provider): Activity
    {
        $this->provider = $provider === 'external' ? 'external' : 'school';
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
     * @return Activity
     */
    public function setType(?string $type): Activity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $listingStart;

    /**
     * @return \DateTime|null
     */
    public function getListingStart(): ?\DateTime
    {
        return $this->listingStart;
    }

    /**
     * @param \DateTime|null $listingStart
     * @return Activity
     */
    public function setListingStart(?\DateTime $listingStart): Activity
    {
        $this->listingStart = $listingStart;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $listingEnd;

    /**
     * @return \DateTime|null
     */
    public function getListingEnd(): ?\DateTime
    {
        return $this->listingEnd;
    }

    /**
     * @param \DateTime|null $listingEnd
     * @return Activity
     */
    public function setListingEnd(?\DateTime $listingEnd): Activity
    {
        $this->listingEnd = $listingEnd;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $programStart;

    /**
     * @return \DateTime|null
     */
    public function getProgramStart(): ?\DateTime
    {
        return $this->programStart;
    }

    /**
     * @param \DateTime|null $programStart
     * @return Activity
     */
    public function setProgramStart(?\DateTime $programStart): Activity
    {
        $this->programStart = $programStart;
        return $this;
    }

    /**
     * @var \DateTime|null
     */
    private $programEnd;

    /**
     * @return \DateTime|null
     */
    public function getProgramEnd(): ?\DateTime
    {
        return $this->programEnd;
    }

    /**
     * @param \DateTime|null $programEnd
     * @return Activity
     */
    public function setProgramEnd(?\DateTime $programEnd): Activity
    {
        $this->programEnd = $programEnd;
        return $this;
    }

    /**
     * @var integer
     */
    private $maxParticipants;

    /**
     * @return int
     */
    public function getMaxParticipants(): int
    {
        return $this->maxParticipants ?: 0;
    }

    /**
     * @param int $maxParticipants
     * @return Activity
     */
    public function setMaxParticipants(int $maxParticipants): Activity
    {
        $this->maxParticipants = $maxParticipants ?: 0;
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
     * @return Activity
     */
    public function setDescription(?string $description): Activity
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @var string|null
     */
    private $payment;

    /**
     * @return null|string
     */
    public function getPayment(): ?string
    {
        return $this->payment;
    }

    /**
     * @param null|string $payment
     * @return Activity
     */
    public function setPayment(?string $payment): Activity
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @var array
     */
    public static $paymentTypeList = [
        'entire_programme',
        'per_session',
        'per_week',
    ];

    /**
     * @var string|null
     */
    private $paymentType;

    /**
     * @return null|string
     */
    public function getPaymentType(): ?string
    {
        return in_array($this->paymentType, self::$paymentTypeList) ? $this->paymentType : 'entire_programme';
    }

    /**
     * @param null|string $paymentType
     * @return Activity
     */
    public function setPaymentType(?string $paymentType): Activity
    {
        $paymentType = in_array($paymentType, self::$paymentTypeList) ? $paymentType : 'entire_programme';
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @var array
     */
    public static $paymentFirmnessList = [
        'finalised',
        'estimated',
    ];


    /**
     * @var string|null
     */
    private $paymentFirmness;

    /**
     * @return null|string
     */
    public function getPaymentFirmness(): ?string
    {
        return $this->paymentFirmness ?: 'finalised';
    }

    /**
     * @param null|string $paymentFirmness
     * @return Activity
     */
    public function setPaymentFirmness(?string $paymentFirmness): Activity
    {
        $this->paymentFirmness = $paymentFirmness === 'estimated' ? 'estimated' : 'finalised';
        return $this;
    }

    /**
     * @var SchoolYear|null
     */
    private $schoolYear;

    /**
     * @return SchoolYear|null
     */
    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    /**
     * @param SchoolYear|null $schoolYear
     * @return Activity
     */
    public function setSchoolYear(?SchoolYear $schoolYear): Activity
    {
        $this->schoolYear = $schoolYear;
        return $this;
    }

    /**
     * @var Collection
     */
    private $schoolYearTerms;

    /**
     * @return Collection
     */
    public function getSchoolYearTerms(): Collection
    {
        return $this->schoolYearTerms;
    }

    /**
     * @param Collection $schoolYearTerms
     * @return Activity
     */
    public function setSchoolYearTerms(Collection $schoolYearTerms): Activity
    {
        $this->schoolYearTerms = $schoolYearTerms;
        return $this;
    }

    /**
     * @var Collection
     */
    private $yearGroups;

    /**
     * @return Collection
     */
    public function getYearGroups(): Collection
    {
        return $this->yearGroups;
    }

    /**
     * @param Collection $yearGroups
     * @return Activity
     */
    public function setYearGroups(Collection $yearGroups): Activity
    {
        $this->yearGroups = $yearGroups;
        return $this;
    }
}