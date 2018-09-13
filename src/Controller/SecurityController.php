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
 * Date: 10/09/2018
 * Time: 09:45
 */
namespace App\Controller;

use App\Form\Type\ActionType;
use App\Form\Type\PersonRoleType;
use App\Manager\ActionManager;
use App\Manager\PersonRoleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends Controller
{
    /**
     * manageRole
     *
     * @param PersonRoleManager $manager
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/person/security/role/{id}/manage/", name="manage_roles")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manageRole(PersonRoleManager $manager, Request $request, $id = 0)
    {
        $form = $this->createForm(PersonRoleType::class, $manager->find($id ?: 'Add'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
            $manager->saveEntity();

        return $this->render('Security/manage_roles.html.twig',
            array(
                'manager'    => $manager,
                'fullForm' => $form,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * managePermissions
     *
     * @param ActionManager $manager
     * @param string $groupBy
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/person/security/permissions/manage/", name="manage_permissions")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function managePermissions(ActionManager $manager, string $groupBy = '%')
    {
        $actions = $manager->getList();
        return $this->render('Security/permissions.html.twig',
            array(
                'manager' => $manager,
                'actions' => $actions,
            )
        );
    }

    /**
     * createEditPermission
     *
     * @param ActionManager $manager
     * @param Request $request
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/security/permission/{id}/edit/", name="create_edit_permission")
     * @Security("is_granted('USE_ROUTE', ['manage_permissions', {}])")
     */
    public function createEditPermission(ActionManager $manager, Request $request, $id = '0')
    {
        $entity = $manager->find($id ?: 'Add');
        $form = $this->createForm(ActionType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->saveEntity();
        }

        return $this->render('Security/create_edit_permission.html.twig',
            array(
                'manager'    => $manager,
                'fullForm' => $form,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * changePermission
     *
     * @param ActionManager $manager
     * @param int $id
     * @param string $role
     * @param TranslatorInterface $translator
     * @return JsonResponse
     * @throws \Exception
     * @Route("/action/{id}/permission/{role}/toggle/", name="toggle_action_permission")
     * @Security("is_granted('USE_ROUTE', ['manage_permissions', {}])")
     */
    public function changePermission(ActionManager $manager, int $id, string $role, TranslatorInterface $translator)
    {
        $manager->find($id);

        $manager->togglePermission($role);

        return new JsonResponse(
            [
                'messages' => $manager->getMessageManager()->serialiseTranslatedMessages($translator),
            ],
            200);
    }
}