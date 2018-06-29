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
 * Date: 8/06/2018
 * Time: 13:23
 */
namespace App\Controller;

use App\Manager\ExternalAssessmentManager;
use App\test\Chained;
use App\test\TestFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlatypusController extends Controller
{
    /**
     * home
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     */
    public function home(ExternalAssessmentManager $eam)
    {
        $chain = new Chained();

        $form = $this->createForm(TestFormType::class, $chain);

        return $this->render('home.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}