<?php
namespace App\Twig\Extension;

use App\Entity\SchoolYear;
use App\Manager\SchoolYearManager;
use App\Manager\UserManager;
use App\Util\PersonNameHelper;
use App\Util\UserHelper;
use Symfony\Component\Security\Core\User\UserInterface;
use Twig\Extension\AbstractExtension;

class UserExtension extends AbstractExtension
{
	/**
	 * @var UserManager
	 */
	private $userManager;

	/**
	 * FormErrorsExtension constructor.
	 *
	 * @param UserManager $userManager
	 */
	public function __construct(UserManager $userManager, PersonNameHelper $personNameHelper)
	{
		$this->userManager = $userManager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('getFullUserName', [$this, 'getFullUserName']),
			new \Twig_SimpleFunction('get_userManager', [$this, 'getUserManager']),
            new \Twig_SimpleFunction('get_CurrentSchoolYear', [$this, 'getCurrentSchoolYear']),
            new \Twig_SimpleFunction('get_CurrentSchoolYearName', [$this, 'getCurrentSchoolYearName']),
			new \Twig_SimpleFunction('get_UserSetting', [$this->userManager, 'getUserSetting']),
		];
	}

	/**
	 * Get User Manager
	 *
	 * @return  UserManager
	 */
	public function getUserManager()
	{
		return $this->userManager;
	}

    /**
     * getName
     *
     * @return string
     */
    public function getName()
	{
		return 'user_manager_extension';
	}

    /**
     * getCurrentSchoolYear
     *
     * @return SchoolYear|null
     */
    public function getCurrentSchoolYear(): ?SchoolYear
    {
        return UserHelper::getCurrentSchoolYear();
    }

    /**
     * getCurrentSchoolYearName
     *
     * @return string
     */
    public function getCurrentSchoolYearName(): string
    {
        if ($this->getCurrentSchoolYear() instanceof SchoolYear)
            return $this->getCurrentSchoolYear()->getName();
        return 'Unknown';
    }

    /**
     * getFullUserName
     *
     * @param null|UserInterface $user
     * @return string
     */
    public function getFullUserName(?UserInterface $user): string
    {
        return PersonNameHelper::getFullUserName($user);
    }
}