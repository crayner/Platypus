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
 * Time: 13:22
 */
namespace App\Pagination;

use App\Entity\Family;
use App\Entity\FamilyMemberAdult;
use App\Entity\FamilyMemberChild;
use App\Manager\FamilyManager;
use App\Manager\MessageManager;
use App\Util\PersonNameHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FamilyPagination
 * @package App\Pagination
 */
class FamilyPagination extends PaginationReactManager
{
    /**
     * @var string
     */
    protected $name = 'Family';

    /**
     * @var string
     */
    protected $alias = 'f';

    /**
     * @var int
     */
    protected $limit = 25;

    /**
     * @var string
     */
    protected $entityName = Family::class;

    /**
     * @var array
     */
    protected $join = [];

    /**
     * @var string
     */
    protected $transDomain = 'Person';

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

        $families = [];
        foreach($results as $family) {
            $family['children'] = '';
            $family['adults'] = '';
            $families[$family['id']] = $family;
        }
        $people = $this->getRepository(FamilyMemberChild::class)->createQueryBuilder('x')
            ->leftJoin('x.family', 'f')
            ->leftJoin('x.person', 'p')
            ->select('f.id as family_id, p.id as person_id, p.surname, p.firstName, p.title, p.preferredName')
            ->orderBy('p.dob')
            ->where('p.id IS NOT NULL')
            ->getQuery()
            ->getArrayResult();
        foreach($people as $person)
        {
            $family = $families[$person['family_id']];
            $family['children'] .= '<br/>' . PersonNameHelper::getFullName($person, ['preferredOnly' => true, 'surnameFirst' => false]);
            $family['children'] = trim($family['children'], '<br/>');
            $families[$person['family_id']] = $family;
        }
        $people = $this->getRepository(FamilyMemberAdult::class)->createQueryBuilder('x')
            ->leftJoin('x.family', 'f')
            ->leftJoin('x.person', 'p')
            ->select('f.id as family_id, p.id as person_id, p.surname, p.firstName, p.title, p.preferredName')
            ->orderBy('x.sequence')
            ->where('p.id IS NOT NULL')
            ->getQuery()
            ->getArrayResult();
        foreach($people as $person)
        {
            $family = $families[$person['family_id']];
            $family['adults'] .= '<br/>' . PersonNameHelper::getFullName($person, ['preferredOnly' => true, 'surnameFirst' => false]);
            $family['adults'] = trim($family['adults'], '<br/>');
            $families[$person['family_id']] = $family;
        }

        return array_merge($families, []);
    }

    /**
     * @var FamilyManager
     */
    private $familyManager;

    /**
     * PaginationReactManager constructor.
     * @param RequestStack $request
     */
    public function __construct(RequestStack $request, EntityManagerInterface $entityManager, MessageManager $messageManager, FamilyManager $familyManager)
    {
        parent::__construct($request,  $entityManager, $messageManager);
        $this->familyManager = $familyManager;
    }

    /**
     * @return FamilyManager
     */
    public function getFamilyManager(): FamilyManager
    {
        return $this->familyManager;
    }

    /**
     * @var array|null
     */
    protected $searchDefinition = [
        'name',
        'status',
        'adults',
        'children',
    ];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'f.name' => [
            'label' => 'family.name.label',
            'name' => 'name',
            'size' => 2,
        ],
        'f.status' => [
            'label' => 'family.status.label',
            'name' => 'status',
            'size' => 2,
            'translate' => 'family.status.'
        ],
        'adults' => [
            'label' => 'family.adults.label',
            'name' => 'adults',
            'size' => 3,
            'select' => false,
            'style' => 'html',
        ],
        'children' => [
            'label' => 'family.children.label',
            'name' => 'children',
            'size' => 3,
            'select' => false,
            'style' => 'html',
        ],
        'f.id' => [
            'label' => false,
            'display' => false,
            'name' => 'id',
        ],
    ];

    /**
     * @var array
     */
    protected $actions = [
        'size' => 2,
        'buttons' => [
            [
                'label' => 'family.action.delete',
                'url' => '/family/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'classMerge' => 'btn-sm',
            ],
            [
                'label' => 'family.action.edit',
                'url' => '/family/__id__/edit/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
                'classMerge' => 'btn-sm',
            ],
        ],
    ];

    /**
     * @var array
     */
    protected $headerDefinition = [
        'buttons' => [
            [
                'label' => 'Add Family',
                'url' => '/family/Add/edit/',
                'url_options' => [],
                'type' => 'add',
                'response_type' => 'redirect',
            ],
        ],
    ];

    /**
     * @var array
     */
    protected $sortByList = [
        'family.name.label' => [
            'name',
        ],
    ];

    /**
     * setSpecificTranslations
     *
     * @return PaginationInterface
     */
    protected function setSpecificTranslations(): PaginationInterface
    {
        if (empty($this->specificTranslations))
            $this->specificTranslations = [];

        foreach(Family::getStatusList() as $value)
            $this->specificTranslations[] = 'family.status.' . $value;
        $this->specificTranslations[] = 'family.action.edit';
        $this->specificTranslations[] = 'family.action.delete';

        return $this;
    }
}