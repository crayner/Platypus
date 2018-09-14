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
 * Date: 14/09/2018
 * Time: 11:27
 */
namespace App\Manager\Gibbon;

use App\Entity\Scale;
use App\Entity\ScaleGrade;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GibbonScaleManager
 * @package App\Manager\Gibbon
 */
class GibbonScaleManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        Scale::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonScale';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonScaleID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 40,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 5,
            ],
        ],
        'usage' => [
            'field' => 'usage_details',
            'functions' => [
                'length' => 50,
            ],
        ],
        'active' => [
            'field' => 'active',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'numeric' => [
            'field' => 'is_numeric',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'lowestAcceptable' => [
            'field' => 'lowest_acceptable',
            'functions' => [
                'call' => ['function' => 'setLowestAcceptable'],
                'nullable' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';

    /**
     * setLowestAcceptable
     *
     * @param $value
     * @param $options
     * @return int|null
     */
    public function setLowestAcceptable($value, $options, ObjectManager $manager): ?int
    {
        if (empty($value))
            return null;
        $result = $manager->getRepository(ScaleGrade::class)->createQueryBuilder('g')
            ->where('g.scale = :scale_id')
            ->select('g.id')
            ->setParameter('scale_id', $options['datum']['gibbonScaleID'])
            ->andWhere('g.sequence = :sequence')
            ->setParameter('sequence', $value)
            ->getquery()
            ->getOneOrNullResult();
        return empty($result['id']) ? null : $result['id'] ;
    }
}
