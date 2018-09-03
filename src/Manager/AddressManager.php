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
 * Date: 28/08/2018
 * Time: 13:24
 */
namespace App\Manager;

use App\Entity\Address;
use App\Entity\Family;
use App\Entity\Locality;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class AddressManager
 * @package App\Manager
 */
class AddressManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Address::class;

    /**
     * @var array
     */
    private $translations = [
        'address.help.header',
        'address.help.content',
        'address.no.parent',
        'address.add',
        'address.remove',
        'address.search.placeholder',
        'address.button.add',
        'address.edit.header',
        'address.edit.content',
        'address.property_name.label',
        'address.street_name.label',
        'address.building_type.label',
        'address.street_number.label',
        'address.building_number.label',
        'address.locality.label',
        'address.locality.help',
        'address.locality.placeholder',
        'locality.button.edit',
        'locality.edit.header',
        'locality.name.label',
        'locality.post_code.label',
        'locality.territory.label',
        'locality.country.label',
        'locality.country.placeholder',
        'locality.territory.placeholder',
        'locality.button.save',
        'locality.button.exit',
        'address.button.edit',
        'address.button.exit',
        'address.post_code.help',
        'address.post_code.label',
        'address.edit.help',
    ];

    /**
     * @var SettingManager
     */
    static private $settingManager;

    /**
     * getSettingManager
     *
     * @return SettingManager
     */
    public static function getSettingManager(): SettingManager
    {
        return self::$settingManager;
    }

    /**
     * setSettingManager
     *
     * @param SettingManager $settingManager
     */
    public static function setSettingManager(SettingManager $settingManager): void
    {
        self::$settingManager = $settingManager;
    }

    /**
     * getTranslations
     *
     * @return array
     */
    public function getTranslations(): array
    {
        foreach(self::getBuildingTypeList() as $type)
            $this->translations[] = 'address.building_type.'.$type;
        return $this->translations;
    }

    /**
     * grabAllAddresses
     *
     * @return array
     */
    public function grabAllAddresses(): array
    {
        $results = $this->getRepository()->createQueryBuilder('a')
            ->leftJoin('a.locality', 'l')
            ->getQuery()->getResult();
        $xx = [];
        foreach ($results as $address) {
            $x = [];
            $x['id'] = $address->getId();
            $x['label'] = $address->__toString();
            $xx[$x['id']] = $x;
        }

        return $xx;
    }

    /**
     * attachAddressToParentEntity
     *
     * @param $id
     * @param $parentEntity
     * @param $entity_id
     * @return AddressManager
     * @throws \Exception
     */
    public function attachAddressToParentEntity($id, $parentEntity, $entity_id): AddressManager
    {
        $address = $this->find($id);
        $this->grabAddressesOfParentEntity($parentEntity, $entity_id);

        if (!$address instanceof Address) {
            $this->getMessageManager()->add('warning', 'address.not.found', [], 'Address');
            return $this;
        }
        if ($this->getEntityAddresses()->contains($address))
            return $this;
        if ($parentEntity === 'Person')
            return $this->attachAddressToPerson($address, $entity_id);
        if ($parentEntity === 'Family')
            return $this->attachAddressToFamily($address, $entity_id);
    }

    /**
     * attachAddressToPerson
     *
     * @param Address $address
     * @param int $id
     * @return AddressManager
     * @throws \Exception
     */
    private function attachAddressToPerson(Address $address, int $id): AddressManager
    {
        $person = $this->getRepository(Person::class)->find($id);
        if ($person instanceof Person) {
            $person->addAddress($address);
            $this->getEntityManager()->persist($person);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
        return $this;
    }

    /**
     * attachAddressToFamily
     *
     * @param Address $address
     * @param int $id
     * @return AddressManager
     * @throws \Exception
     */
    private function attachAddressToFamily(Address $address, int $id): AddressManager
    {
        $family = $this->getRepository(Family::class)->find($id);
        if ($family instanceof Family) {
            $family->addAddress($address);
            $this->getEntityManager()->persist($family);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
        return $this;
    }

    /**
     * @var ArrayCollection
     */
    var $entityAddresses;

    /**
     * grabAddressesOfParentEntity
     *
     * @param string $parentEntity
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function grabAddressesOfParentEntity(string $parentEntity, int $id): array
    {
        if ($parentEntity === 'Person')
            return $this->grabAddressesOfPerson($id);
        if ($parentEntity === 'Family')
            return $this->grabAddressesofFamily($id);
    }

    /**
     * grabAddressesOfPerson
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    private function grabAddressesOfPerson($id): array
    {
        $person = $this->getRepository(Person::class)->find($id);

        if (!$person instanceof Person) {
            $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
            return [];
        }

        $addresses = [];
        $entityAddresses = [];
        foreach ($person->getAddresses() as $address) {
            $add = [];
            $add['id'] = $address->getId();
            $add['label'] = $address->__toString();
            $add['parent'] = 'Person';
            $addresses[] = $add;
            $entityAddresses[] = $address;
        }

        foreach ($person->getFamilies() as $family) {
            $addresses = array_merge($addresses, $this->grabAddressesofFamily($family->getFamily()->getId()));
            $entityAddresses = array_merge($entityAddresses, $this->entityAddresses->toArray());
        }

        $this->entityAddresses = new ArrayCollection($entityAddresses);

        return $addresses;
    }

    /**
     * grabAddressesOfFamily
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    private function grabAddressesOfFamily($id): array
    {
        $family = $this->getRepository(Family::class)->find($id);

        if (!$family instanceof Family) {
            $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
            return [];
        }
        $addresses = [];
        $entityAddresses = [];
        foreach ($family->getAddresses() as $address) {
            $add = [];
            $add['id'] = $address->getId();
            $add['label'] = $address->__toString();
            $add['parent'] = 'Family';
            $addresses[] = $add;
            $entityAddresses[] = $address;

        }

        $this->entityAddresses = new ArrayCollection($entityAddresses);
        return $addresses;
    }

    /**
     * removeAddressFromParentEntity
     *
     * @param $id
     * @param $parentEntity
     * @param $entity_id
     * @return AddressManager
     * @throws \Exception
     */
    public function removeAddressFromParentEntity($id, $parentEntity, $entity_id): AddressManager
    {
        $address = $this->find($id);
        if (!$address instanceof Address) {
            $this->getMessageManager()->add('warning', 'address.not.found', [], 'Address');
            return $this;
        }
        if ($parentEntity === 'Person')
            return $this->removeAddressFromPerson($address, $entity_id);
        if ($parentEntity === 'Family')
            return $this->removeAddressFromFamily($address, $entity_id);
    }

    /**
     * removeAddressFromPerson
     *
     * @param Address $address
     * @param int $id
     * @return AddressManager
     * @throws \Exception
     */
    private function removeAddressFromPerson(Address $address, int $id): AddressManager
    {
        $person = $this->getRepository(Person::class)->find($id);
        if ($person instanceof Person) {
            $person->removeAddress($address);
            $this->getEntityManager()->persist($person);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'person.not.found', [], 'Address');
        return $this;
    }

    /**
     * removeAddressFromFamily
     *
     * @param Address $address
     * @param int $id
     * @return AddressManager
     * @throws \Exception
     */
    private function removeAddressFromFamily(Address $address, int $id): AddressManager
    {
        $family = $this->getRepository(Family::class)->find($id);
        if ($family instanceof Family) {
            $family->removeAddress($address);
            $this->getEntityManager()->persist($family);
            $this->getEntityManager()->flush();
            return $this;
        }
        $this->getMessageManager()->add('warning', 'family.not.found', [], 'Address');
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEntityAddresses(): ArrayCollection
    {
        return $this->entityAddresses;
    }

    /**
     * @var LocalityManager
     */
    private $localityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * AddressManager constructor.
     * @param SettingManager $settingManager
     * @param LocalityManager $localityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(SettingManager $settingManager, LocalityManager $localityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $settingManager->getEntityManager();
        $this->messageManager = $settingManager->getMessageManager();
        self::$entityRepository = $this->entityManager->getRepository($this->entityName);
        self::$settingManager = $settingManager;
        $this->localityManager = $localityManager;
        $this->validator = $validator;
        $this->localityManager->setValidator($validator);
    }

    /**
     * getBuildingTypeList
     *
     * @return array
     */
    public static function getBuildingTypeList(): array
    {
        return Address::getBuildingTypeList();
    }

    /**
     * getAddressProperties
     *
     * @return array
     */
    public function getAddressProperties(): array
    {
        return [
            'buildingTypeList' => self::getBuildingTypeList(),
            'localityList' => self::getLocalityList(),
        ];
    }

    /**
     * getBuildingTypeList
     *
     * @return array
     */
    public static function getLocalityList(): array
    {
        return self::getSettingManager()->getEntityManager()->getRepository(Locality::class)->createQueryBuilder('l', 'l.id')
            ->select(["CONCAT(l.name, ' ', l.territory, ' ', l.postCode) AS label", 'l.id'])
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * findLocality
     *
     * @param $id
     * @return Locality|null
     * @throws \Exception
     */
    public function findLocality($id): ?Locality
    {
        return $this->getLocalityManager()->find($id);
    }

    /**
     * @return LocalityManager
     */
    public function getLocalityManager(): LocalityManager
    {
        return $this->localityManager;
    }

    /**
     * saveLocality
     *
     * @param Locality $entity
     * @return AddressManager
     */
    public function saveLocality(Locality $entity): AddressManager
    {
        $this->getLocalityManager()->save($entity);
        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * save
     *
     * @param Address $entity
     * @return AddressManager
     */
    public function save(Address $entity): AddressManager
    {
        $errors = $this->getValidator()->validate($entity);

        foreach($errors as $error)
            $this->getMessageManager()->add('danger', $error->getPropertyPath().': '.$error->getMessage());

        if ($errors->count() === 0)
            $this->setEntity($entity)->saveEntity();

        return $this;
    }
}
