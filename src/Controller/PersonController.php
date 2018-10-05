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
 * Date: 12/06/2018
 * Time: 10:54
 */
namespace App\Controller;

use App\Form\PreferencesType;
use App\Form\Type\PersonFieldType;
use App\Form\Type\PersonType;
use App\Manager\AddressManager;
use App\Manager\PersonFieldManager;
use App\Manager\PersonManager;
use App\Manager\PhoneManager;
use App\Manager\ThemeManager;
use App\Pagination\PersonPagination;
use App\Util\AssetHelper;
use App\Util\PersonHelper;
use Hillrange\Security\Exception\UserException;
use Hillrange\Security\Form\ChangePasswordType;
use Hillrange\Security\Util\PasswordManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PersonController extends Controller
{
    /**
     * preferences
     *
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @param PasswordManager $passwordManager
     * @param PersonHelper $personHelper
     * @param ThemeManager $themeManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/preferences/", name="preferences")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function preferences(AuthenticationUtils $authUtils, Request $request, PasswordManager $passwordManager, PersonHelper $personHelper, ThemeManager $themeManager)
    {
        $user = $this->getUser();

        $this->get('doctrine')->getManager()->refresh($user);

        $translator = $this->get('translator');

        $error = $authUtils->getLastAuthenticationError();

        if (empty($user))
            throw new \Symfony\Component\Security\Core\Exception\InvalidArgumentException('The user was not found.');

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $passwordManager->saveNewPassword($user, $form->get('plainPassword')->get('first')->getData());
            $error = new UserException($translator->trans('security.password.forced.success', [], 'security'));
        }

        $settingForm = null;
        PersonHelper::setUser($user);
        PersonHelper::hasPerson();
        if (PersonHelper::hasPerson())
        {
            $settingForm = $this->createForm(PreferencesType::class, PersonHelper::getPerson(), ['deleteBackgroundImage' => $this->get('router')->generate('preference_delete_background', ['id' => PersonHelper::getPerson()->getId()])]);

            $settingForm->handleRequest($request);

            if ($settingForm->isSubmitted() && $settingForm->isValid()) {
                $em = PersonHelper::getEntityManager();
                $em->persist(PersonHelper::getPerson());
                if (PersonHelper::hasStaff()){
                    $staff = PersonHelper::getStaff();
                    $staff->setSmartWorkflowHelp($settingForm->get('smartWorkflowHelp')->getData());
                    $em->persist($staff);
                }
                $em->flush();
            }
        }

        return $this->render('Person/preferences.html.twig',
            [
                'password_form'  => $form->createView(),
                'error' => $error,
                'passwordManager' => $passwordManager,
                'setting_form' => $settingForm instanceof FormInterface ? $settingForm->createView() : null,
            ]
        );
    }

    /**
     * managePeople
     *
     * @param PersonPagination $personPagination
     * @param PersonHelper $manager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/people/all/list/", name="manage_people")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function managePeople(PersonPagination $personPagination, PersonHelper $manager)
    {
        return $this->render('Person/list.html.twig',
            array(
                'pagination' => $personPagination,
                'manager'    => $manager,
            )
        );
    }

    /**
     * delete
     *
     * @param int $id
     * @param PersonPagination $personPagination
     * @param PersonManager $manager
     * @return JsonResponse
     * @throws \Exception
     * @Route("/person/{id}/delete/", name="person_delete"))
     * @Security("is_granted('USE_ROUTE', ['manage_people'])")
     */
    public function delete(int $id, PersonPagination $personPagination, PersonManager $manager)
    {
        $manager->delete($id);

        return new JsonResponse(
            [
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($this->get('translator')),
                'rows' => $personPagination->getAllResults(),
            ],
            200
        );
    }

    /**
     * edit
     *
     * @param PersonManager $manager
     * @param Request $request
     * @param AddressManager $addressManager
     * @param PhoneManager $phoneManager
     * @param string $id
     * @param string $tabName
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/person/{id}/edit/{tabName}", name="person_edit"))
     * @Security("is_granted('USE_ROUTE', ['manage_people'])")
     */
    public function edit(PersonManager $manager, Request $request, AddressManager $addressManager, PhoneManager $phoneManager, $id = 'Add', $tabName = 'basic.information')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(PersonType::class, $entity, ['manager' => $manager]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->getEntityManager()->persist($entity);
            $manager->getEntityManager()->flush();
            $manager->reset();
            $form = $this->createForm(PersonType::class, $entity, ['manager' => $manager]);
        }

        return $this->render(
            'Person/edit.html.twig',
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
     * deletePersonalBackground
     *
     * @param AssetHelper $assetHelper
     * @param PersonHelper $personHelper
     * @param int $id
     * @Route("/user/preference/{id}/delete_personal_background/", name="preference_delete_background")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deletePersonalBackground(AssetHelper $assetHelper, PersonManager $manager, int $id)
    {
        $person = $manager->find($id);
        AssetHelper::removeAsset($person->getPersonalBackground());
        $person->setPersonalBackground(null);
        return $this->redirectToRoute('preferences');
    }

    /**
     * deletePersonalImage
     *
     * @param AssetHelper $assetHelper
     * @param AssetHelper $assetHelper
     * @param PersonManager $manager
     * @param string $getImageMethod
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @Route("/person/{id}/image/{getImageMethod}/delete/{tabName}", name="personal_image_remover")
     * @Security("is_granted('USE_ROUTE', ['manage_people'])")
     */
    public function removePersonalImage(AssetHelper $assetHelper, PersonManager $manager, string $getImageMethod, int $id, string $tabName = 'basic.information')
    {
        $person = $manager->find($id);
        AssetHelper::removeAsset($person->$getImageMethod());
        $setImageMethod = 's'.mb_substr($getImageMethod, 1);
        $person->$setImageMethod(null);
        return $this->redirectToRoute('person_edit', ['id' => $id, 'tabName' => $tabName]);
    }

    /**
     * manageCustomFields
     *
     * @param PersonFieldManager $manager
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/person/custom/field/{id}/manage/", name="manage_custom_fields")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manageCustomFields(PersonFieldManager $manager, Request $request, $id = 0)
    {
        $form = $this->createForm(PersonFieldType::class, $manager->find($id ?: 'Add'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
            $manager->saveEntity();

        return $this->render('Person/custom_fields.html.twig',
            array(
                'manager'    => $manager,
                'fullForm' => $form,
                'form' => $form->createView(),
            )
        );
    }
}