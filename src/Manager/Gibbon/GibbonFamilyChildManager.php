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
 * Time: 13:00
 */
namespace App\Manager\Gibbon;

use App\Entity\Family;
use App\Entity\FamilyPerson;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GibbonFamilyChildManager
 * @package App\Manager\Gibbon
 */
class GibbonFamilyChildManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonFamilyChild';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */
    protected $entityName = [
        FamilyPerson::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [
        FamilyPerson::class => true,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonFamilyChildID' => [
            'field' => '',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonFamilyID' => [
            'field' => 'family_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonPersonID' => [
            'field' => 'person_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'comment' => [
            'field' => 'comment',
        ],
    ];

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @return array
     */
    public function postRecord(string $entityName, array $newData): array
    {
        $newData['person_type'] = 'child';
        return $newData;
    }

    /**
     * postLoad
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function postLoad(string $entityName, ObjectManager $manager)
    {
        $result = $manager->getRepository(FamilyPerson::class)->createQueryBuilder('x')
            ->leftJoin('x.person', 'p')
            ->leftJoin('x.family', 'f')
            ->where('p.id IS NULL')
            ->orWhere('f.id IS NULL')
            ->select('x.id')
            ->getQuery()
            ->getArrayResult()
        ;
        $meta = $manager->getClassMetadata($entityName);

        foreach($result as $id)
            $manager->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);

        $result = $manager->getRepository(Family::class)->createQueryBuilder('f')
            ->leftJoin('f.members', 'm')
            ->where('m.id IS NULL')
            ->select('f.id')
            ->getQuery()
            ->getArrayResult()
        ;

        $meta = $manager->getClassMetadata(Family::class);

        foreach($result as $id)
            $manager->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);
    }
}
