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
 * Date: 9/06/2018
 * Time: 17:45
 */

namespace App\Organism;


class Language
{
    /**
     * @var string
     */
    private $language = 'en_GB';

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Language
     */
    public function setLanguage(string $language): Language
    {
        $this->language = $language;
        return $this;
    }
}