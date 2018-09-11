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
 * Date: 11/09/2018
 * Time: 08:17
 */
namespace App\Form\Transformer;

use App\Organism\ActionRouteParam;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ActionRouteParamTransformer
 * @package App\Form\Transformer
 */
class ActionRouteParamTransformer implements DataTransformerInterface
{
    /**
     * transform
     *
     * @param mixed $value
     * @return ArrayCollection
     */
    public function transform($value)
    {
        if (empty($value) || ! is_array($value))
            return new ArrayCollection();

        $results = new ArrayCollection();

        $y = 1;
        foreach($value as $param)
        {
            $x = new ActionRouteParam();
            $x->setId($y++);
            $x->setName($param['name']);
            $x->setValue($param['value']);
            $results->add($x);
        }

        return $results;
    }

    /**
     * reverseTransform
     *
     * @param mixed $value
     * @return array
     */
    public function reverseTransform($value)
    {
        if (empty($value) || !$value instanceof ArrayCollection)
            return [];

        if ($value->count() === 0)
            return [];

        $results = [];
        foreach($value->getIterator() as $param){
            $w = [];
            $w['name'] = $param->getName();
            $w['value'] = $param->getValue();
            $results[] = $w;
        }

        return $results;
    }
}