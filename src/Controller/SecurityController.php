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

use App\Form\Type\PersonRoleType;
use App\Manager\PersonRoleManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @IsGranted("ROLE_ADMIN")
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
}