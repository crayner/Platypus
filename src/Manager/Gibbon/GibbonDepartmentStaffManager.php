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
 * Date: 19/09/2018
 * Time: 11:16
 */
namespace App\Manager\Gibbon;

use App\Entity\DepartmentStaff;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GibbonDepartmentStaffMember
 * @package App\Manager\Gibbon
 */
class GibbonDepartmentStaffManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonDepartmentStaff';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRollGroup';

    /**
     * @var array
     */
    protected $entityName = [
        DepartmentStaff::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonDepartmentStaffID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonPersonID' => [
            'field' => 'member_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonDepartmentID' => [
            'field' => 'dept_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'role' => [
            'field' => 'role',
            'functions' => [
                'safeString' => '',
                'call' => ['function' => 'getRoleName'],
            ],
        ],
    ];

    /**
     * getRoleName
     *
     * @param $value
     * @return mixed
     */
    public function getRoleName($value)
    {
        if (in_array($value,DepartmentStaff::getRoleList()))
            return $value;

        switch ($value){
            case 'teacher_(curriculum)':
                return 'teacher_curriculum';
                break;
            default:
                dump(DepartmentStaff::getRoleList());
                dd($value);
        }
    }

    /**
     * postLoad
     *
     * @param string $entityName
     * @param ObjectManager $this->getObjectManager()
     */
    public function postLoad(string $entityName)
    {
        $result = $this->getObjectManager()->getRepository(DepartmentStaff::class)->createQueryBuilder('x')
            ->leftJoin('x.member', 'p')
            ->leftJoin('x.department', 'd')
            ->where('p.id IS NULL')
            ->orWhere('d.id IS NULL')
            ->select('x.id')
            ->getQuery()
            ->getArrayResult()
        ;
        $meta = $this->getObjectManager()->getClassMetadata($entityName);

        foreach($result as $id)
            $this->getObjectManager()->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);
    }
}