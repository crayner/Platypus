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

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed|object
     */
    public function reverseTransform($value)
    {
        switch ($this->type) {
            case 'array':
                if (empty($value))
                    return null;
                return $value;
                break;
            case 'html':
            case 'boolean':
            case 'integer':
            case 'colour':
            case 'image':
            case 'choice':
                return $value;
                break;
            default:
                trigger_error('Deal with setting type ' . $this->type, E_USER_ERROR);
        }
    }

    /**
     * @var string
     */
    private $type;

    /**
     * SettingValueTransformer constructor.
     * @param null|string $type
     */
    public function __construct(?string $type = 'string')
    {
        $this->type = $type;
    }
}