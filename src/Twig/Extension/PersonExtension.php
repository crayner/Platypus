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
 * Date: 10/08/2018
 * Time: 15:09
 */
namespace App\Twig\Extension;

use App\Manager\PersonManager;
use Twig\Extension\AbstractExtension;

/**
 * Class PersonExtension
 * @package App\Twig\Extension
 */
class PersonExtension extends AbstractExtension
{
    /**
     * @var PersonManager
     */
    private $personManager;

    /**
     * PersonExtension constructor.
     * @param PersonManager $personManager
     */
    public function __construct(PersonManager $personManager)
    {
        $this->personManager = $personManager;
    }

    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getStaffList', [$this->personManager, 'getStaffList']),
        ];
    }

}