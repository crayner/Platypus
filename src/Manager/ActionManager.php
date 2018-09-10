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
 * Date: 10/09/2018
 * Time: 11:38
 */
namespace App\Manager;

use App\Entity\Action;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Action::class;

    /**
     * getList
     *
     * @return array
     * @throws \Exception
     */
    public function getList(): array
    {
        return $this->getRepository()->createQueryBuilder('a')
            ->orderBy('a.groupBy', 'ASC')
            ->addOrderBy('a.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * parseRouteParams
     *
     * @param array $params
     * @return array
     */
    public function parseRouteParams(array $params): array
    {
        $results = [];
        foreach($params as $param)
        {
            $resolver = new OptionsResolver();
            $resolver->setRequired(['name','value']);
            $resolver->resolve($param);
            $results[$param['name']] = $param['value'];
        }
        return $results;
    }

    /**
     * parseRouteParams
     *
     * @param array $params
     * @return array
     */
    public function dumpRouteParams(): array
    {
        $results = [];
        foreach($this->getEntity()->getRouteParams() as $name=>$value)
        {
            $result = [];
            $result['name'] = $name;
            $result['value'] = $value;
            $results[] = $result;
        }
        return $results;
    }

}