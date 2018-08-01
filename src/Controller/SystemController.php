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
 * Date: 31/07/2018
 * Time: 14:15
 */
namespace App\Controller;

use App\Form\SectionSettingType;
use App\Manager\MultipleSettingManager;
use App\Manager\ScaleManager;
use App\Manager\SettingManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SystemController
 * @package App\Controller
 */
class SystemController extends Controller
{
    /**
     * systemSettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @param MultipleSettingManager $multipleSettingManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     * @Route("/system/settings/manage/", name="manage_system_settings")
     * @IsGranted("ROLE_REGISTRAR")
     */
    public function systemSettings(Request $request, SettingManager $sm, MultipleSettingManager $multipleSettingManager, ScaleManager $scaleManager)
    {
        $request->getSession()->set('manage_settings', $sm->createSettingDefinition('System', ['request' => $request]));

        $settings = $request->getSession()->get('manage_settings');
        foreach ($settings->getSections() as $name =>$section)
            if ($name === 'header')
                $multipleSettingManager->setHeader($section);
            else
                $multipleSettingManager->addSection($section);

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multipleSettingManager->saveSections($sm, $request->request->get('section'));
            return $this->redirectToRoute('manage_system_settings');
        }

        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }
}