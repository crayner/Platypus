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
        $manager->grabAllAddresses();
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
     * @return JsonResponse
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

}