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
 * Date: 10/06/2018
 * Time: 12:21
 */
namespace App\Controller;

use App\Repository\SettingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{

    /**
     * @param         $name
     * @param Request $request
     *
     * @return Response
     * @Route("/setting/{name}/edit/{closeWindow}", name="setting_edit_name")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function editName($name, $closeWindow = null,  Request $request, SettingRepository $settingRepository)
    {
        $setting = null;
        $original = $name;
        do
        {
            $setting = $settingRepository->findOneByName($name);

            if (is_null($setting))
            {
                $name = explode('.', $name);
                array_pop($name);
                $name = implode('.', $name);
            }

        } while (is_null($setting) && false !== mb_strpos($name, '.'));


        if (is_null($setting))
            throw new \InvalidArgumentException('The System setting of name: ' . $original . ' was not found');

        return $this->forward(SettingController::class.'::edit', ['id' => $setting->getId(), 'closeWindow' => $closeWindow]);
    }

}