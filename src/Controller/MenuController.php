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
 * Date: 9/06/2018
 * Time: 10:57
 */
namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MenuController
{
    /**
     * @Route("/menu/section/{display}/display/", name="section_menu_display")
     * @param $display
     * @param Request $request
     * @return JsonResponse
     */
    public function sectionMenuDisplay($display, Request $request)
    {
        if ($request->hasSession())
            $request->getSession()->set('showSectionMenu', $display === 'hide' ? 'hide' : 'show');

        return JsonResponse::create('', 200);
    }

}