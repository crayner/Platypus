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
 * User' => 'craig
 * Date' => '10/08/2018
 * Time' => '15:11
 */
namespace App\Manager;

use App\Entity\Person;
use App\Entity\PersonRole;
use App\Manager\Traits\EntityTrait;
use App\Util\PersonNameHelper;
use Hillrange\Security\Entity\User;

/**
 * Class PersonManager
 * @package App\Manager
 */
class PersonManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Person::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'basic.information',
            'label' => 'basic.information.tab',
            'include' => 'Person/basic_information.html.twig',
            'message' => 'basicInformationMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'system.access',
            'label' => 'system.access.tab',
            'include' => 'Person/system_access.html.twig',
            'message' => 'systemAccessMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'contact.information',
            'label' => 'contact.information.tab',
            'include' => 'Person/contact_information.html.twig',
            'message' => 'contactInformationMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'school.information',
            'label' => 'school.information.tab',
            'include' => 'Person/school_information.html.twig',
            'message' => 'schoolInformationMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'background.information',
            'label' => 'background.information.tab',
            'include' => 'Person/background_information.html.twig',
            'message' => 'backgroundInformationMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'employment',
            'label' => 'employment.tab',
            'include' => 'Person/employment.html.twig',
            'message' => 'employmentInformationMessage',
            'translation' => 'Person',
            'display' => 'isParent',
        ],
        [
            'name' => 'enrolment',
            'label' => 'Enrolment',
            'include' => 'Person/Enrolment',
            'message' => 'enrolmentInformationMessage',
            'translation' => 'Person',
            'display' => 'isStudent',
        ],
        [
            'name' => 'miscellaneous',
            'label' => 'miscellaneous.tab',
            'include' => 'Person/miscellaneous.html.twig',
            'message' => 'miscellaneousInformationMessage',
            'translation' => 'Person',
        ],
    ];

    /**
     * getFullName
     *
     * @param array $options
     * @return string
     */
    public function getFullName(array $options = []): string
    {
        return PersonNameHelper::getFullName($this->getEntity(), $options);
    }

    /**
     * isStudent
     *
     * @return bool
     */
    public function isStudent(?Person $person = null): bool
    {
        if (empty($person))
            $person = $this->getEntity();
        if (! $person instanceof Person )
            return false;
        $role = $person->getPrimaryRole();
        if (! $role instanceof PersonRole)
            return false;
        if ($role->getCategory() === 'student')
            return true;
        return false;
    }

    /**
     * @var boolean|null
     */
    private $staff;

    /**
     * isStaff
     *
     * @param Person|null $person
     * @return bool
     */
    public function isStaff(?Person $person = null): bool
    {
        if (is_bool($this->staff))
            return $this->staff;
        if (empty($person))
            $person = $this->getEntity();
        if (! $person instanceof Person )
            return $this->staff = false;
        $role = $person->getPrimaryRole();
        if (! $role instanceof PersonRole)
            return $this->staff = false;
        dump($role);
        if ($role->getCategory() === 'staff')
            return $this->staff = true;
        return $this->staff = false;
    }

    /**
     * isUser
     *
     * @param Person|null $person
     * @return bool
     */
    public function isUser(?Person $person = null): bool
    {
        if (empty($person))
            $person = $this->getEntity();
        if (! $person instanceof Person )
            return false;
        return $person->isCanLogin();
    }

    /**
     * canBeUser
     *
     * @param Person|null $person
     * @return bool
     */
    public function canBeUser(?Person $person = null): bool
    {
        if ($this->isUser())
            return true;
        if (empty($person))
            $person = $this->getEntity();
        if (! $person instanceof Person )
            return false;
        if (empty($person->getEmail()))
            return false;
        $count = $this->getRepository(User::class)->findByEmailCanonical($person->getEmail());
        if (empty($count))
            return true;

        return false;
    }

    /**
     * isParent
     *
     * @param Person|null $person
     * @return bool
     */
    public function isParent(?Person $person = null): bool
    {
        if (empty($person))
            $person = $this->getEntity();
        if (! $person instanceof Person )
            return false;
        $role = $person->getPrimaryRole();
        if (! $role instanceof PersonRole)
            return false;
        if (in_array($role->getCategory(), ['parent']))
            return true;

        foreach($person->getSecondaryRoles() as $role)
            if (in_array($role->getCategory(), ['parent']))
                return true;
        return false;
    }

    /**
     * reset
     *
     * @return PersonManager
     */
    public function reset(): PersonManager
    {
        $this->staff = null;

        return $this;
    }
}