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
 * Date: 30/08/2018
 * Time: 16:29
 */
namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;

class FormExtension extends AbstractExtension
{

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return 'form_extension';
    }

    /**
     * getFunctions
     *
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('formTranslations', array($this, 'formTranslations')),
        ];
    }

    /**
     * formTranslations
     *
     * @return array
     */
    public function formTranslations(): array
    {
        return [
            'form.required',
        ];
    }
}