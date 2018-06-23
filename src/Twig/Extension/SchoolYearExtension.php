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
 * Date: 23/06/2018
 * Time: 08:48
 */
namespace App\Twig\Extension;

use App\Entity\SchoolYear;
use App\Util\SchoolYearHelper;
use Twig\Extension\AbstractExtension;

/**
 * Class SchoolYearExtension
 * @package App\Twig\Extension
 */
class SchoolYearExtension extends AbstractExtension
{

    /**
     * CoreExtension constructor.
     * @param SchoolYearHelper $helper
     */
    public function __construct(SchoolYearHelper $helper)
    {
    }

    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getCurrentSchoolYear', [$this, 'getCurrentSchoolYear']),
        ];
    }

    /**
     * getCurrentSchoolYear
     *
     * @return SchoolYear
     */
    public function getCurrentSchoolYear(): SchoolYear
    {
        return SchoolYearHelper::getCurrentSchoolYear();
    }
}