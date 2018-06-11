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

class ActivityStudent
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
     * @return ActivityStudent
     */
    public function setId(?int $id): ActivityStudent
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var Activity|null
     */
    private $activity;

    /**
     * @return Activity|null
     */
    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    /**
     * @param Activity|null $activity
     * @return ActivityStudent
     */
    public function setActivity(?Activity $activity): ActivityStudent
    {
        $this->activity = $activity;
        return $this;
    }

    /**
     * @var Person|null
     */
    private $person;

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }

    /**
     * @param Person|null $person
     * @return ActivityStudent
     */
    public function setPerson(?Person $person): ActivityStudent
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @var array
     */
    public static $statusList = [
        'Accepted',
        'Pending',
        'Waiting List',
        'Not Accepted'
    ];

    /**
     * @var string|null
     */
    private $status;

    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return in_array($this->status, self::$statusList) ? $this->status : 'Pending';
    }

    /**
     * @param null|string $status
     * @return ActivityStudent
     */
    public function setStatus(?string $status): ActivityStudent
    {
        $this->status = in_array($status, self::$statusList) ? $status : 'Pending';;
        return $this;
    }

    /**
     * @var \DateTime
     */
    private $timestamp;

    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     * @return ActivityStudent
     */
    public function setTimestamp(\DateTime $timestamp): ActivityStudent
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @var boolean|null
     */
    private $invoiceGenerated;

    /**
     * @return bool|null
     */
    public function getInvoiceGenerated(): ?bool
    {
        return $this->invoiceGenerated ? true : false;
    }

    /**
     * @param bool|null $invoiceGenerated
     * @return ActivityStudent
     */
    public function setInvoiceGenerated(?bool $invoiceGenerated): ActivityStudent
    {
        $this->invoiceGenerated = $invoiceGenerated ? true : false;
        return $this;
    }

    /**
     * @var Activity|null
     */
    private $activityBackup;

    /**
     * @return Activity|null
     */
    public function getActivityBackup(): ?Activity
    {
        return $this->activityBackup;
    }

    /**
     * @param Activity|null $activityBackup
     * @return ActivityStudent
     */
    public function setActivityBackup(?Activity $activityBackup): ActivityStudent
    {
        $this->activityBackup = $activityBackup;
        return $this;
    }
}