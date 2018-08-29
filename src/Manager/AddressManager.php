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
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;

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
    ];

    /**
     * @return array
     */
    public function getTranslations(): array
    {
        return $this->translations;
    }

    /**
     * grabAllAddresses
     *
     * @return array
     */
    public function grabAllAddresses(): array
    {
        // , " ", l.name, " ", l.territory, " ", a.postCode, l.postCode
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
        if (!$address instanceof Address) {
            $this->getMessageManager()->add('warning', 'address.not.found', [], 'Address');
            return $this;
        }
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
        foreach ($person->getAddresses() as $address) {
            $add = [];
            $add['id'] = $address->getId();
            $add['label'] = $address->__toString();
            $add['parent'] = 'Person';
            $addresses[] = $add;
        }

        foreach ($person->getFamilies() as $family) {
            $addresses = array_merge($addresses, $this->grabAddressesofFamily($family->getFamily()->getId()));
        }
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
        foreach ($family->getAddresses() as $address) {
            $add = [];
            $add['id'] = $address->getId();
            $add['label'] = $address->__toString();
            $add['parent'] = 'Family';
            $addresses[] = $add;
        }

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
}
