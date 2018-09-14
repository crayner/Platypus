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
 * Date: 22/08/2018
 * Time: 08:42
 */
namespace App\Entity;

/**
 * Class FamilyMember
 * @package App\Entity
 */
abstract class FamilyMember
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
     * @return FamilyMember
     */
    public function setId(?int $id): FamilyMember
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @var string|null
     */
    private $comment;

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     * @return FamilyMember
     */
    public function setComment(?string $comment): FamilyMember
    {
        $this->comment = $comment;
        return $this;
    }
}