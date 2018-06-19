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
 * Date: 16/06/2018
 * Time: 10:16
 */
namespace App\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SettingValueTransformer
 * @package App\Form\Transformer
 */
class SettingValueTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function transform($value)
    {
        if (empty($value))
            return null;

        if (is_array($value))
            return Yaml::dump($value);

        if ($value instanceof File)
            return $value;

        if (is_object($value))
            dd($value);

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed|object
     */
    public function reverseTransform($value)
    {
        if (empty($value))
            return null;
        return $value;
    }
}