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

use Hillrange\Security\Exception\UserException;
use Hillrange\Security\Form\ChangePasswordType;
use Hillrange\Security\Util\PasswordManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     */
    public function preferences(AuthenticationUtils $authUtils, Request $request, PasswordManager $passwordManager)
    {
        $user = $this->getUser();

        $translator = $this->get('translator');

        $error = $authUtils->getLastAuthenticationError();

        if (empty($user))
            throw new \Symfony\Component\Security\Core\Exception\InvalidArgumentException('The user was not found.');

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $passwordManager->saveNewPassword($user);
            $error = new UserException($translator->trans('security.password.forced.success', [], 'security'));
        }

        return $this->render('Person/preferences.html.twig',
            [
                'password_form'  => $form->createView(),
                'error' => $error,
                'passwordManager' => $passwordManager,
            ]
        );
    }
}