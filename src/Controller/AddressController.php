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
 * Date: 29/08/2018
 * Time: 11:33
 */
namespace App\Controller;

use App\Manager\AddressManager;
use App\Manager\LocalityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class AddressController
 * @package App\Controller
 */
class AddressController
{
    /**
     * grabAllAddresses
     *
     * @param AddressManager $manager
     * @return JsonResponse
     * @Route("/address/grab/list/", name="address_list_grab")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAllAddresses(AddressManager $manager)
    {
        return new JsonResponse(
            [
                'data' => $manager->grabAllAddresses(),
            ],
            200
        );
    }

    /**
     * attachAddress
     *
     * @param int $entity_id
     * @param int $id
     * @param string $parentEntity
     * @param AddressManager $manager
     * @return JsonResponse
     * @Route("/address/{id}/attach/{parentEntity}/{entity_id}/", name="address_attach")
     * @IsGranted("ROLE_ADMIN")
     */
    public function attachAddress(int $entity_id, int $id, string $parentEntity, AddressManager $manager, TranslatorInterface $translator)
    {

        $messages = $manager->attachAddressToParentEntity($id, $parentEntity, $entity_id)->getMessageManager()->serialiseTranslatedMessages($translator);
        return new JsonResponse(
            [
                'messages' => $messages,
                'addresses' => $manager->grabAddressesOfParentEntity($parentEntity, $entity_id),
            ],
            200
        );
    }

    /**
     * grabAttachedAddresses
     *
     * @param int $id
     * @param string $parentEntity
     * @param AddressManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/address/attached/{parentEntity}/{id}/", name="grab_attached_address")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAttachedAddresses(int $id, string $parentEntity, AddressManager $manager, TranslatorInterface $translator)
    {
        return new JsonResponse(
            [
                'addresses' => $manager->grabAddressesOfParentEntity($parentEntity, $id),
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }

    /**
     * removeAddress
     *
     * @param int $entity_id
     * @param int $id
     * @param string $parentEntity
     * @param AddressManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/address/{id}/remove/{parentEntity}/{entity_id}/", name="address_remove")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removeAddress(int $entity_id, int $id, string $parentEntity, AddressManager $manager, TranslatorInterface $translator)
    {
        $messages = $manager->removeAddressFromParentEntity($id, $parentEntity, $entity_id)->getMessageManager()->serialiseTranslatedMessages($translator);
        return new JsonResponse(
            [
                'messages' => $messages,
            ],
            200
        );
    }

    /**
     * grabLocality
     *
     * @param int $id
     * @param LocalityManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/locality/{id}/grab/", name="grab_locality")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabLocality(int $id, LocalityManager $manager)
    {
        return new JsonResponse(
            [
                'locality' => $manager->find($id)->toArray(),
            ],
            200
        );
    }

    /**
     * saveLocality
     *
     * @param $locality
     * @param AddressManager $manager
     * @return JsonResponse
     * @Route("/locality/{locality}/save/", name="save_locality")
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveLocality($locality, AddressManager $manager, TranslatorInterface $translator)
    {

        $locality = json_decode(base64_decode($locality));

        $entity = $manager->findLocality($locality->id ?: 'Add');
        $entity->setName($locality->name);
        $entity->setCountry($locality->country);
        $entity->setTerritory($locality->territory);
        $entity->setPostCode($locality->postCode);
        $manager->saveLocality($entity);
        return new JsonResponse(
            [
                'status' => $manager->getMessageManager()->getStatus(),
                'locality' => $entity->toArray(),
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }
    
    /**
     * grabAllAddresses
     *
     * @param AddressManager $manager
     * @return JsonResponse
     * @Route("/locality/grab/list/", name="locality_list_grab")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAllLocalities(AddressManager $manager)
    {
        return new JsonResponse(
            [
                'data' => $manager->getLocalityList(),
            ],
            200
        );
    }

    /**
     * grabAddress
     *
     * @param int $id
     * @param AddressManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/address/{id}/grab/", name="grab_address")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAddress(int $id, AddressManager $manager)
    {
        return new JsonResponse(
            [
                'address' => $manager->find($id)->toArray(),
            ],
            200
        );
    }

    /**
     * saveAddress
     *
     * @param $address
     * @param AddressManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/address/{address}/save/", name="save_address")
     * @IsGranted("ROLE_ADMIN")
     */
    public function saveAddress(string $address, AddressManager $manager, TranslatorInterface $translator)
    {
        $address = json_decode(base64_decode($address));

        $entity = $manager->find($address->id ?: 'Add');
        $entity->setBuildingType($address->buildingType);
        $entity->setBuildingNumber($address->buildingType);
        $entity->setStreetName($address->streetName);
        $entity->setStreetNumber($address->streetNumber);
        $entity->setPostCode($address->postCode);
        $entity->setPropertyName($address->propertyName);
        $entity->setLocality($manager->findLocality($address->locality));
        $manager->save($entity);
        return new JsonResponse(
            [
                'status' => $manager->getMessageManager()->getStatus(),
                'address' => $entity->toArray(),
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }
}