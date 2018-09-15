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
 * Date: 15/09/2018
 * Time: 07:59
 */
namespace App\Entity;

/**
 * Class FamilyMemberChild
 * @package App\Entity
 */
class FamilyMemberChild extends FamilyMember
{
    /**
     * @var Family|null
     */
    private $family;

    /**
     * @return Family|null
     */
    public function getFamily(): ?Family
    {
        return $this->family;
    }

    /**
     * setFamily
     *
     * @param Family|null $family
     * @param bool $add
     * @return FamilyMember
     */
    public function setFamily(?Family $family, bool $add = true): FamilyMember
    {
        if ($add && $family instanceof Family)
            $family->addChildMember($this, false);

        $this->family = $family;
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
     * setPerson
     *
     * @param Person|null $person
     * @param bool $add
     * @return FamilyMember
     */
    public function setPerson(?Person $person, bool $add = true): FamilyMember
    {
        if ($add && $person instanceof Person)
            $person->addChildFamily($this, false);

        $this->person = $person;
        return $this;
    }
}