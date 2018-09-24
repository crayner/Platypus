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
     * @Route("/gibbon/transfer/{section}/", name="gibbon_transfer")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function transfer(GibbonManager $manager, string $section = 'start')
    {
        $logger = $this->get('monolog.logger.demonstration');

        $gm = $this->getDoctrine()->getManager('gibbon');
        $manager->setGibbonEntityManager($gm);
        $objectManager = $this->getDoctrine()->getManager();
        $sql = 'SHOW TABLES';

        $done = false;
        foreach($gm->getConnection()->fetchAll($sql) as $table)
            foreach($table as $name) {
                if ($done)
                    $this->redirectToRoute('gibbon_transfer', ['section' => $name]);
                if ($section === 'start' || $section === $name) {
                    $manager->transfer($name, $this->get('monolog.logger.gibbon'));
                    $done = true;
                }
            }

        return $this->redirectToRoute('home');
    }
}