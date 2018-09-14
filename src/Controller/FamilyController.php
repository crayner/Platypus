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

use App\Pagination\FamilyPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/family/all/list/", name="manage_families")
     * @Security("is_granted('ROLE_ACTION', request)")
     */
    public function manageFamily(FamilyPagination $pagination) {
        return $this->render('Family/list.html.twig',
            array(
                'pagination' => $pagination,
                'manager'    => $pagination->getFamilyManager(),
            )
        );
    }
}