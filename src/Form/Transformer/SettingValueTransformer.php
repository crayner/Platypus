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

use PhpParser\Node\Stmt\Else_;
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
        if (is_array($value) && $this->type === 'array') {
            if (empty($value))
                return '';
            return Yaml::dump($value);
        }
        if (empty($value) && $this->type === 'multiEnum')
            return [];

        if ($this->type === 'multiEnum' && ! is_array($value))
            dd($value);

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
        if (empty($value) && $this->type === 'multiEnum')
            return [];

        if ($this->type === 'array')
            $value = self::handleArrayValue($value);

        return $value;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * SettingValueTransformer constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * handleArrayValue
     *
     * @param $value
     * @return string
     */
    public static function handleArrayValue($value): string
    {
        $x = str_replace(["\\r", "\\n", '"'], ['', "\n", ''],$value);
        $y = preg_match_all('/\n/', $x) + preg_match_all('/\\n/', $x);
        if ($y === 0 && ! empty($x) && preg_match_all('/,/', $x) > 0)
        {
            $t = '';
            foreach(explode(',',$x) as $w) {
                $t .= "- '" . $w . "'\n";
            }
            return str_replace("''", "'", $t);
        }
        return $x;
    }
}