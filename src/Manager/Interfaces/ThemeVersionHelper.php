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
 * Date: 4/08/2018
 * Time: 09:02
 */
namespace App\Manager\Interfaces;


interface ThemeVersionHelper
{
    /**
     * getVersion
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string;

    /**
     * getName
     *
     * @return string
     */
    public function getTemplateName(): string;

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * getAuthor
     *
     * @return string
     */
    public function getAuthor(): string;

    /**
     * getURL
     *
     * @return string
     */
    public function getURL(): string;
}