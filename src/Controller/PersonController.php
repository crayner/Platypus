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
use App\Form\Type\PersonType;
use App\Manager\PersonManager;
use App\Manager\PersonRoleManager;
use App\Manager\StaticManager;
use App\Manager\ThemeManager;
use App\Manager\UserManager;
use App\Pagination\PersonPagination;
use App\Util\AssetHelper;
use App\Util\PersonHelper;
use Hillrange\Security\Exception\UserException;
use Hillrange\Security\Form\ChangePasswordType;
use Hillrange\Security\Util\PasswordManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PersonController extends Controller
{

    /**
     * userEdit
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/security/user/edit/{id}/", name="user_edit")
     */
    public function userEdit(int $id)
    {
        return $this->redirectToRoute('person_edit', ['id' => $id, '_fragment' => 'user']);
    }

    /**
     * preferences
     * @Route("/user/preferences/", name="preferences")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @param PasswordManager $passwordManager
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
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
            $settingForm =  $this->createForm(PreferencesType::class, PersonHelper::getPerson(), ['deleteBackgroundImage' => $this->get('router')->generate('preference_delete_background', ['id' => PersonHelper::getPerson()->getId()])]);

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
     * index
     *
     * @param Request $request
     * @param PersonPagination $personPagination
     * @param PersonManager $personManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/people/all/list/", name="manage_people")
     * @IsGranted("ROLE_TEACHER")
     */
    public function index(PersonPagination $personPagination, PersonHelper $manager)
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
     * @Route("/person/{id}/delete/", name="person_delete"))
     * @IsGranted("ROLE_ADMIN")
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
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/person/{id}/edit/", name="person_edit"))
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(PersonManager $manager, Request $request,  $id = 'Add')
    {
        $entity = $manager->find($id);

        $form = $this->createForm(PersonType::class, $entity, ['deletePhoto' => $this->generateUrl('delete_personal_photo', ['id' => $id])]);

        $manager->getTabs();

        $form->handleRequest($request);

        return $this->render(
            'Person/edit.html.twig',
            [
                'form' => $form->createView(),
                'manager' => $manager,
            ]
        );
    }

    /**
     * deletePersonalPhoto
     *
     * @Route("/person/{id}/delete_personal_photo/", name="delete_personal_photo")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param AssetHelper $assetHelper
     * @param PersonManager $manager
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function deletePersonalPhoto(AssetHelper $assetHelper, PersonManager $manager, int $id)
    {
        $person = $manager->find($id);
        AssetHelper::removeAsset($person->getPhoto());
        $person->setPhoto(null);
        return $this->redirectToRoute('person_edit', ['id' => $id]);
    }
}