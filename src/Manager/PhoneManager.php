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
 * Date: 2/09/2018
 * Time: 11:55
 */
namespace App\Manager;

use App\Entity\Family;
use App\Entity\Person;
use App\Entity\Phone;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class PhoneManager
 * @package App\Manager
 */
class PhoneManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Phone::class;

    /**
     * @var array
     */
    private $translations = [
        'phone.search.placeholder',
        'phone.button.add',
        'phone.button.edit',
        'phone.button.save',
        'phone.remove',
        'phone.type.label',
        'phone.number.label',
        'phone.country.label',
        'phone.edit.header',
        'phone.button.exit',
        'phone.edit.help',
        'phone.help.header',
        'phone.help.content',
        'family.button.link',
        'phone.no.family',
    ];

    /**
     * getTranslations
     *
     * @return array
     */
    public function getTranslations(): array
    {
        foreach(Phone::getPhoneTypeList() as $type)
            $this->translations[] = 'phone.type.' . $type;

        return $this->translations;
    }

    /**
     * grabAllPhoneNumbers
     *
     * @return array
     */
    public function grabAllPhoneNumbers(): array
    {
        return $this->getRepository()->createQueryBuilder('p', 'p.id')
            ->select(['p.id','p.phoneNumber','p.countryCode'])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * attachPhoneToParentEntity
     *
     * @param $id
     * @param $parentEntity
     * @param $entity_id
     * @return PhoneManager
     * @throws \Exception
     */
    public function attachPhoneToParentEntity($id, $parentEntity, $entity_id): PhoneManager
    {
        $phone = $this->find($id);
        $this->grabPhonesOfParentEntity($parentEntity, $entity_id);

        if (!$phone instanceof Phone) {
            $this->getMessageManager()->add('warning', 'phone.not.found', [], 'Phone');
            return $this;
        }
        if ($this->getEntityPhones()->contains($phone))
            return $this;
        if ($parentEntity === 'Person')
            return $this->attachPhoneToPerson($phone, $entity_id);
        if ($parentEntity === 'Family')
            return $this->attachPhoneToFamily($phone, $entity_id);
    }

    /**
     * grabPhonesOfParentEntity
     *
     * @param string $parentEntity
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function grabPhonesOfParentEntity(string $parentEntity, int $id): array
    {
        if ($parentEntity === 'Person')
            return $this->grabPhonesOfPerson($id);
        if ($parentEntity === 'Family')
            return $this->grabPhonesofFamily($id);
    }

    /**
     * @var ArrayCollection
     */
    var $entityPhones;

    /**
     * @return ArrayCollection
     */
    public function getEntityPhones(): ArrayCollection
    {
        return $this->entityPhones = $this->entityPhones ?: new ArrayCollection();
    }

    /**
     * attachPhoneToPerson
     *
     * @param Phone $phone
     * @param int $id
     * @return PhoneManager
     * @throws \Exception
     */
    private function attachPhoneToPerson(Phone $phone, int $id): PhoneManager
    {
        $person = $this->getRepository(Person::class)->find($id);
        if ($person instanceof Person) {
            $person->addPhone($phone);
            $this->getEntityManager()->persist($person);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
        return $this;
    }

    /**
     * attachPhoneToFamily
     *
     * @param Phone $phone
     * @param int $id
     * @return PhoneManager
     * @throws \Exception
     */
    private function attachPhoneToFamily(Phone $phone, int $id): PhoneManager
    {
        $family = $this->getRepository(Family::class)->find($id);
        if ($family instanceof Family) {
            $family->addPhone($phone);
            $this->getEntityManager()->persist($family);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
        return $this;
    }

    /**
     * grabPhonesOfPerson
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    private function grabPhonesOfPerson($id): array
    {
        $person = $this->getRepository(Person::class)->find($id);

        if (!$person instanceof Person) {
            $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
            return [];
        }

        $phones = [];
        $entityPhones = [];
        foreach ($person->getPhones() as $phone) {
            $add = $phone->toArray();
            $add['parent'] = 'Person';
            $phones[] = $add;
            $entityPhones[] = $phone;
        }

        foreach ($person->getFamilies() as $family) {
            $phones = array_merge($phones, $this->grabPhonesofFamily($family->getFamily()->getId()));
            $entityPhones = array_merge($entityPhones, $this->entityPhones->toArray());
        }

        $this->entityPhones = new ArrayCollection($entityPhones);

        return $phones;
    }

    /**
     * grabPhonesOfFamily
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    private function grabPhonesOfFamily($id): array
    {
        $family = $this->getRepository(Family::class)->find($id);

        if (!$family instanceof Family) {
            $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
            return [];
        }
        $phones = [];
        $entityPhones = [];
        foreach ($family->getPhones() as $phone) {
            $add = $phone->toArray();
            $add['parent'] = 'Family';
            $phones[] = $add;
            $entityPhones[] = $phone;

        }

        $this->entityPhones = new ArrayCollection($entityPhones);
        return $phones;
    }

    /**
     * getPhoneProperties
     *
     * @return array
     */
    public function getPhoneProperties(): array
    {
        return [
            'phoneTypeList' => Phone::getPhoneTypeList(),
        ];
    }

    /**
     * save
     *
     * @param Phone $entity
     * @return PhoneManager
     */
    public function save(Phone $entity): PhoneManager
    {
        $errors = $this->getValidator()->validate($entity);

        foreach($errors as $error)
            $this->getMessageManager()->add('danger', $error->getPropertyPath().': '.$error->getMessage());

        if ($errors->count() === 0)
            $this->setEntity($entity)->saveEntity();

        return $this;
    }

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * PhoneManager constructor.
     * @param SettingManager $settingManager
     * @param ValidatorInterface $validator
     */
    public function __construct(SettingManager $settingManager, ValidatorInterface $validator)
    {
        $this->entityManager = $settingManager->getEntityManager();
        $this->messageManager = $settingManager->getMessageManager();
        self::$entityRepository = $this->entityManager->getRepository($this->entityName);
        $this->validator = $validator;
    }

    /**
     * getValidator
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * removePhoneFromParentEntity
     *
     * @param $id
     * @param $parentEntity
     * @param $entity_id
     * @return PhoneManager
     * @throws \Exception
     */
    public function removePhoneFromParentEntity($id, $parentEntity, $entity_id): PhoneManager
    {
        $phone = $this->find($id);
        if (!$phone instanceof Phone) {
            $this->getMessageManager()->add('warning', 'phone.not.found', [], 'Phone');
            return $this;
        }
        if ($parentEntity === 'Person')
            return $this->removePhoneFromPerson($phone, $entity_id);
        if ($parentEntity === 'Family')
            return $this->removePhoneFromFamily($phone, $entity_id);
    }

    /**
     * removePhoneFromPerson
     *
     * @param Phone $phone
     * @param int $id
     * @return PhoneManager
     * @throws \Exception
     */
    private function removePhoneFromPerson(Phone $phone, int $id): PhoneManager
    {
        $person = $this->getRepository(Person::class)->find($id);
        if ($person instanceof Person) {
            $person->removePhone($phone);
            $this->getEntityManager()->persist($person);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
        return $this;
    }

    /**
     * removePhoneFromFamily
     *
     * @param Phone $phone
     * @param int $id
     * @return PhoneManager
     * @throws \Exception
     */
    private function removePhoneFromFamily(Phone $phone, int $id): PhoneManager
    {
        $family = $this->getRepository(Family::class)->find($id);
        if ($family instanceof Family) {
            $family->removePhone($phone);
            $this->getEntityManager()->persist($family);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
        return $this;
    }
}