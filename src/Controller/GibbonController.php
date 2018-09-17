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
 * Date: 12/09/2018
 * Time: 11:19
 */
namespace App\Controller;

use App\Manager\GibbonManager;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GibbonController
 * @package App\Controller
 */
class GibbonController extends Controller
{
    /**
     * transfer
     * @Route("/gibbon/transfer/{section}/")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function transfer(GibbonManager $manager, ObjectManager $objectManager, string $section = 'start')
    {
        $logger = $this->get('monolog.logger.demonstration');
        if ($section === 'start')
            $section = 'GibbonSettingManager';

        $manager->transfer($section, $this->get('monolog.logger.gibbon'));

        return $this->render('Gibbon/transfer.html.twig',
            [
                'manager' => $manager,
            ]
        );
    }
}