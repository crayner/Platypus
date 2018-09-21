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
 * Date: 20/09/2018
 * Time: 15:22
 */
namespace App\Manager\Gibbon;

use App\Entity\StudentEnrolment;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class GibbonStudentEnrolmentManager
 * @package App\Manager\Gibbon
 */
class GibbonStudentEnrolmentManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonStudentEnrolment';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonCourse';

    /**
     * @var array
     */
    protected $entityName = [
        StudentEnrolment::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonStudentEnrolmentID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonPersonID' => [
            'field' => 'student_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'rollOrder' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonYearGroupID' => [
            'field' => 'year_group_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonRollGroupID' => [
            'field' => 'roll_group_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonSchoolYearID' => [
            'field' => '',
            'functions' => [
                'integer' => '',
            ],
        ],
    ];

    /**
     * postLoad
     *
     * @param string $entityName
     * @param ObjectManager $manager
     */
    public function postLoad(string $entityName, ObjectManager $manager)
    {
        $result = $manager->getRepository(StudentEnrolment::class)->createQueryBuilder('x')
            ->leftJoin('x.student', 's')
            ->leftJoin('x.rollGroup', 'r')
            ->leftJoin('x.yearGroup', 'y')
            ->where('s.id IS NULL')
            ->orWhere('r.id IS NULL')
            ->orWhere('y.id IS NULL')
            ->select('x.id')
            ->getQuery()
            ->getArrayResult()
        ;
        $meta = $manager->getClassMetadata($entityName);

        foreach($result as $id)
            $manager->getConnection()->delete($meta->table['name'], ['id' => $id['id']]);

    }
}
