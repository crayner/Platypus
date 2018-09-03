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
 * Date: 3/09/2018
 * Time: 08:44
 */
namespace App\Controller;

use App\Manager\PhoneManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class PhoneController
 * @package App\Controller
 */
class PhoneController
{
    /**
     * grabAllPhoneNumbers
     *
     * @param PhoneManager $manager
     * @return JsonResponse
     * @Route("/phone/grab/list/", name="phone_list_grab")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAllPhoneNumbers(PhoneManager $manager)
    {
        return new JsonResponse(
            [
                'data' => $manager->grabAllPhoneNumbers(),
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
     * @param PhoneManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/phone/{id}/attach/{parentEntity}/{entity_id}/", name="phone_attach")
     * @IsGranted("ROLE_ADMIN")
     */
    public function attachAddress(int $entity_id, int $id, string $parentEntity, PhoneManager $manager, TranslatorInterface $translator)
    {
        return new JsonResponse(
            [
                'messages' => $manager->attachPhoneToParentEntity($id, $parentEntity, $entity_id)->getMessageManager()->serialiseTranslatedMessages($translator),
                'phones' => $manager->grabPhonesOfParentEntity($parentEntity, $entity_id),
            ],
            200
        );
    }

    /**
     * grabAttachedPhones
     *
     * @param int $id
     * @param string $parentEntity
     * @param PhoneManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/phone/attached/{parentEntity}/{id}/", name="grab_attached_phone")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabAttachedPhones(int $id, string $parentEntity, PhoneManager $manager, TranslatorInterface $translator)
    {
        return new JsonResponse(
            [
                'phones' => $manager->grabPhonesOfParentEntity($parentEntity, $id),
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }

    /**
     * grabPhone
     *
     * @param int $id
     * @param PhoneManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/phone/{id}/grab/", name="grab_phone")
     * @IsGranted("ROLE_ADMIN")
     */
    public function grabPhone(int $id, PhoneManager $manager)
    {
        return new JsonResponse(
            [
                'phone' => $manager->find($id)->toArray(),
            ],
            200
        );
    }

    /**
     * savePhone
     *
     * @param $phone
     * @param PhoneManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/phone/{phone}/save/", name="save_phone")
     * @IsGranted("ROLE_ADMIN")
     */
    public function savePhone(string $phone, PhoneManager $manager, TranslatorInterface $translator)
    {
        $phone = json_decode(base64_decode($phone));

        $entity = $manager->find($phone->id ?: 'Add');
        $entity->setCountryCode($phone->country);
        $entity->setPhoneType($phone->type);
        $entity->setPhoneNumber($phone->phone);
        $manager->save($entity);
        return new JsonResponse(
            [
                'status' => $manager->getMessageManager()->getStatus(),
                'phone' => $entity->toArray(),
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }

    /**
     * removePhone
     *
     * @param int $entity_id
     * @param int $id
     * @param string $parentEntity
     * @param PhoneManager $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/phone/{id}/remove/{parentEntity}/{entity_id}/", name="phone_remove")
     * @IsGranted("ROLE_ADMIN")
     */
    public function removePhone(int $entity_id, int $id, string $parentEntity, PhoneManager $manager, TranslatorInterface $translator)
    {
        return new JsonResponse(
            [
                'messages' => $manager->removePhoneFromParentEntity($id, $parentEntity, $entity_id)->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200
        );
    }
}