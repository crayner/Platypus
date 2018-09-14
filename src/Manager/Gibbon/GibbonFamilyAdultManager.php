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
 * Time: 12:47
 */
namespace App\Manager\Gibbon;

use App\Entity\FamilyMemberAdult;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GibbonFamilyAdultManager
 * @package App\Manager\Gibbon
 */
class GibbonFamilyAdultManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonFamilyAdult';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonFamilyChild';

    /**
     * @var array
     */
    protected $entityName = [
        FamilyMemberAdult::class,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonFamilyAdultID' => [
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
        'childDataAccess' => [
            'field' => 'child_data_access',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'contactPriority' => [
            'field' => 'contact_priority',
            'functions' => [
                'integer' => '',
            ],
        ],
        'contactCall' => [
            'field' => 'contact_call',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'contactSMS' => [
            'field' => 'contact_sms',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'contactEmail' => [
            'field' => 'contact_email',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'contactMail' => [
            'field' => 'contact_mail',
            'functions' => [
                'boolean' => '',
            ],
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
        $newData['member_type'] = 'adult';
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
        $result = $manager->getRepository(FamilyMemberAdult::class)->createQueryBuilder('x')
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
    }
}
