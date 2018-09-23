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
use App\Entity\FamilyMemberChild;
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
    protected $nextGibbonName = 'gibbonStaff';

    /**
     * @var array
     */
    protected $entityName = [
        FamilyMemberChild::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [
        FamilyMemberChild::class => true,
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
        $newData['member_type'] = 'child';
        $records[] = $newData;
        return $records;
    }

    /**
     * postLoad
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function postLoad(string $entityName)
    {
        $result = $this->getObjectManager()->getRepository(FamilyMemberChild::class)->createQueryBuilder('x')
            ->leftJoin('x.person', 'p')
            ->leftJoin('x.family', 'f')
            ->where('p.id IS NULL')
            ->orWhere('f.id IS NULL')
            ->select('x.id')
            ->getQuery()
            ->getArrayResult()
        ;
        $meta = $this->getObjectManager()->getClassMetadata($entityName);
        $this->beginTransaction(true);
        foreach($result as $id)
            $this->getObjectManager()->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);

        $result = $this->getObjectManager()->getRepository(Family::class)->createQueryBuilder('f')
            ->leftJoin('f.childMembers', 'c')
            ->where('c.id IS NULL')
            ->leftJoin('f.adultMembers', 'a')
            ->andWhere('a.id IS NULL')
            ->select('f.id')
            ->getQuery()
            ->getArrayResult()
        ;

        $meta = $this->getObjectManager()->getClassMetadata(Family::class);
        foreach($result as $id)
            $this->getObjectManager()->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);
        $this->commit();
    }
}
