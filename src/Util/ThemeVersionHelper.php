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
 * Time: 08:30
 */
namespace App\Util;

/**
 * Class ThemeVersionHelper
 * @package App\Util
 */
class ThemeVersionHelper implements \App\Manager\Interfaces\ThemeVersionHelper
{
    /**
     * getVersion
     *
     * @return string
     */
    public function getVersion(): string
    {
        return 'Error';
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return '';
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'The theme is not correctly formatted.';
    }

    /**
     * getAuthor
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return '';
    }

    /**
     * getURL
     *
     * @return string
     */
    public function getURL(): string
    {
        return '';
    }

    /**
     * @var
     */
    private $name;
    public function __construct($name)
    {
        $this->name = ucfirst($name);
    }
}