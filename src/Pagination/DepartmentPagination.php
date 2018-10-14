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
 * Date: 23/06/2018
 * Time: 18:07
 */
namespace App\Pagination;

use App\Entity\Department;
use App\Entity\DepartmentStaff;
use App\Util\PersonNameHelper;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DepartmentPagination
 * @package App\Pagination
 */
class DepartmentPagination extends PaginationReactManager
{
    /**
     * @var string
     */
    protected $name = 'Department';

    /**
     * @var string
     */
    protected $alias = 'd';

    /**
     * @var array
     */
    protected $sortByList = [
        'department.sort.name' => [
            'name',
        ],
        'department.sort.type' => [
            'type',
            'name',
        ],
    ];

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var array
     */
    protected $searchList = [
        'fullName',
        'staffList',
    ];

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var string
     * Use array or defaults to entity
     */
    protected $castAs = 'array';

    /**
     * @var string
     */
    protected $entityName = Department::class;

    /**
     * @var string
     */
    protected $transDomain = 'School';

    /**
     * getAllResults
     *
     * @return array
     */
    public function getAllResults(): array
    {
        $results = $this->buildQuery()
            ->getQuery()
            ->getArrayResult();

        foreach($results as $q=>$dept) {
            $staff = $this->getEntityManager()->getRepository(DepartmentStaff::class)->createQueryBuilder('m')
                ->select('m, p.surname, p.firstName, p.title, p.preferredName')
                ->leftJoin('m.department', 'd')
                ->leftJoin('m.member', 'p')
                ->where('d.id = :department')
                ->andWhere('p.id IS NOT NULL')
                ->setParameter('department', $dept['id'])
                ->orderBy('p.surname')
                ->addOrderBy('p.firstName')
                ->getQuery()
                ->getArrayResult();
            ;
            $result = '';
            foreach ($staff as $person)
                $result .= PersonNameHelper::getFullName($person, ['preferredOnly' => true]) . "<br/>\n";
            $results[$q]['staffList'] = ! empty(trim($result, "<br/>\n")) ? trim($result, "<br/>\n") : 'None';
        }

        return $results;

    }

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'fullName' => [
            'label' => 'Name',
            'name' => 'fullName',
            'size' => 3,
            'style' => 'combine',
            'options' => [
                'combine' => ['name' => [], 'nameShort' => ['join' => '<br />'], 'style' => 'html']
            ],
            'select' => ['d.name', 'd.nameShort'],
        ],
        'd.id' => [
            'label' => false,
            'display' => false,
            'name' => 'id',
        ],
        'd.type' => [
            'label' => 'Type',
            'size' => 3,
            'name' => 'type',
            'translate' => 'department.type.',
        ],
        'staffList' => [
            'label' => 'Staff Members',
            'name' => 'staffList',
            'style' => 'html',
            'size' => 3,
            'select' => false,
        ],
    ];

    /**
     * @var array
     */
    protected $actions = [
        'size' => 3,
        'buttons' => [
            [
                'label' => 'Delete Department',
                'url' => '/school/department/{id}/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'response_type' => 'redirect',
                'mergeClass' => 'btn-sm',
            ],
            [
                'label' => 'Edit Department',
                'url' => '/school/department/{id}/edit/{tabName}/',
                'url_options' => [
                    '{id}' => 'id',
                    '{tabName}' => 'tabName',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
                'mergeClass' => 'btn-sm',
            ],
        ],
    ];

    /**
     * @var array|null
     */
    protected $specificTranslations;

    /**
     * setSpecificTranslations
     *
     * @return PaginationInterface
     */
    protected function setSpecificTranslations(): PaginationInterface
    {
        if (empty($this->specificTranslations))
            $this->specificTranslations = [];

        foreach(Department::getTypeList() as $title)
            $this->specificTranslations[] = 'department.type.' . $title;

        return $this;
    }
}
