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
 * Date: 15/10/2018
 * Time: 12:37
 */
namespace App\Manager;

/**
 * Interface FormManagerInterface
 * @package App\Manager
 */
interface FormManagerInterface
{
    /**
     * getTranslationsDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string;
}