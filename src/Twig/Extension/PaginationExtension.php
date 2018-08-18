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
 * Date: 17/08/2018
 * Time: 09:43
 */
namespace App\Twig\Extension;

use App\Pagination\PaginationInterface;
use Twig\Extension\AbstractExtension;

/**
 * Class PaginationExtension
 * @package App\Twig\Extension
 */
class PaginationExtension extends AbstractExtension
{
    /**
     * @var \Twig_Environment
     */
    private $twig;


    /**
     * PaginationExtension constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'pagination_extension';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pagination_script', array($this, 'renderPaginationScript')),
        ];
    }

    /**
     * renderPaginationScript
     *
     * @param PaginationInterface $pagination
     * @return \Twig_Markup
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderPaginationScript(PaginationInterface $pagination)
    {
        $x = $this->twig->render('Pagination/pagination_script.html.twig', ['pagination' => $pagination]);

        return new \Twig_Markup($x, 'html');
    }
}