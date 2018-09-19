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
 * Date: 14/09/2018
 * Time: 13:20
 */
namespace App\Controller;

use App\Form\Type\FamilyType;
use App\Manager\AddressManager;
use App\Manager\FamilyManager;
use App\Manager\PhoneManager;
use App\Pagination\FamilyPagination;
use Hillrange\Security\Util\ParameterInjector;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FamilyController
 * @package App\Controller
 */
class FamilyController extends Controller
{
    /**
     * manageFamily
     *
     * @param FamilyPagination $pagination
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/family/list/", name="manage_families")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function list(FamilyPagination $pagination) {
        return $this->render('Family/list.html.twig',
            array(
                'pagination' => $pagination,
                'manager'    => $pagination->getFamilyManager(),
            )
        );
    }

    /**
     * edit
     *
     * @param FamilyManager $manager
     * @param Request $request
     * @param AddressManager $addressManager
     * @param PhoneManager $phoneManager
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/family/{id}/edit/{tabName}", name="family_edit")
     * @Security("is_granted('USE_ROUTE', ['manage_families'])")
     */
    public function edit(FamilyManager $manager, Request $request, AddressManager $addressManager, PhoneManager $phoneManager, $id = 'Add', $tabName = 'details')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(FamilyType::class, $entity, ['manager' => $manager]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
            $form = $this->createForm(FamilyType::class, $entity, ['manager' => $manager]);
        }

        return $this->render(
            'Family/edit.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
                'manager' => $manager,
                'addressManager' => $addressManager,
                'phoneManager' => $phoneManager,
            ]
        );
    }

    /**
     * delete
     *
     * @param int $id
     * @param FamilyPagination $familyPagination
     * @param FamilyManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/family/{id}/delete/", name="family_delete"))
     * @Security("is_granted('USE_ROUTE', ['manage_families'])")
     */
    public function delete(int $id, FamilyPagination $familyPagination, FamilyManager $manager)
    {
        $manager->delete($id);

        return new JsonResponse(
            [
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($this->get('translator')),
                'rows' => $familyPagination->getAllResults(),
            ],
            200
        );
    }

    /**
     * suggestFamilyName
     *
     * @param FamilyManager $manager
     * @param TranslatorInterface $translator
     * @param ParameterInjector $injector
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/family/{id}/suggest/name/", name="suggest_family_name"))
     * @Security("is_granted('USE_ROUTE', ['manage_families'])")
     */
    public function suggestFamilyName(FamilyManager $manager, TranslatorInterface $translator, ParameterInjector $injector, $id)
    {
        return new JsonResponse($manager->suggestFamilyName($id, $translator),200);
    }
}