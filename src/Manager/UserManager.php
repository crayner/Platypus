<?php
namespace App\Manager;

use App\Calendar\Util\CalendarManager;
use App\Entity\Calendar;
use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Manager\Traits\EntityTrait;
use App\People\Util\PersonManager;
use App\Util\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Security\Entity\User;
use Hillrange\Security\Util\ParameterInjector;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = User::class;

	/**
	 * @var UserInterface
	 */
	private $user;

	/**
	 * @var Person
	 */
	private $person;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var bool
     */
    private $validDatabase = false;

    /**
     * UserManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     */
	public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager, MessageManager $messageManager, ParameterInjector $injector)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        self::$entityRepository = $entityManager->getRepository($this->entityName);
		$this->tokenStorage = $tokenStorage;
		dump($injector::getParameter('database_url'));
		$this->getUser();
	}

    /**
     * getSystemCalendar
     *
     * @return SchoolYear|null
     */
    public function getSystemCalendar(): ?SchoolYear
    {
        return $this->getCurrentCalendar();
    }

    /**
     * getCurrentCalendar
     *
     * @return SchoolYear|null
     */
    public function getCurrentCalendar(): ?SchoolYear
    {
        return UserHelper::getCurrentSchoolYear();
    }

	/**
	 * @param $name
	 *
	 * @return mixed|null
	 */
	public function getUserSetting($name)
	{
		return $this->getUser() instanceof UserInterface ? $this->getUser()->getUserSettings($name) : null;
	}

    /**
     * @return UserInterface|string|null
     */
    public function getUser()
    {
        if (! $this->user)
        {
            if ($this->tokenStorage->getToken())
                $this->user = $this->tokenStorage->getToken()->getUser();
            else
                $this->user = null;
        }

        return $this->user;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * getPerson
     *
     * @return Person|null
     * @throws \Doctrine\ORM\ORMException
     */
    public function getPerson(): ?Person
    {
        if (empty($this->person))
            $this->person = $this->getRepository(Person::class)->findOneByUser($this->getUser());

        return $this->person;
    }

    /**
     * hasPerson
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function hasPerson(): bool
    {
        $this->getPerson();
        if ($this->person instanceof Person)
            return true;
        return false;
    }

    /**
     * @param User $user
     * @return UserManager
     */
    public function setUser(UserInterface $user): UserManager
    {
        $this->user = $user;
        $this->person = null;
        return $this;
    }

    /**
     * isStaff
     *
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function isStaff(): bool
    {
        if ($this->getUser() === 'anon.' || ! $this->hasPerson())
            return false;
        return true;
    }
}