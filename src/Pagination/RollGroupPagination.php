<?php
namespace App\Pagination;

use App\Entity\RollGroup;
use App\Util\SchoolYearHelper;

/**
 * Class RollGroupPagination
 * @package App\Pagination
 */
class RollGroupPagination extends PaginationReactManager
{
	/**
	 * @var string
	 */
	protected $name = 'RollGroup';

	/**
	 * @var string
	 */
	protected $alias = 'r';

    /**
     * @var string
     */
    protected $entityName = RollGroup::class;

	/**
	 * @var array
	 */
	protected $sortByList = [
		'roll_group.sort.name' => [
			'name',
		],
	];

	/**
	 * @var int
	 */
	protected $limit = 50;

    /**
     * @var array
     */
	protected $join = [
        'r.facility' => [
            'alias' => 'f',
            'type' => 'leftJoin',
        ],
        'r.schoolYear' => [
            'alias' => 'y',
            'type' => 'leftJoin',
        ],
    ];

    /**
     * @var string
     * Use array or defaults to entity
     */
    protected $castAs = 'array';

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
        $results = $this->getRepository()->findBy(['schoolYear' => SchoolYearHelper::getCurrentSchoolYear()]);

        $content = [];

        foreach($results as $result)
            {
            $rg = [];
            $ss = '';
            $rg['id'] = $result->getId();
            $rg['name'] = $result->getName();
            $rg['nameShort'] = $result->getNameShort();
            $ss .= $rg['name'].'|'.$rg['nameShort'].'|';
            $rg['facilityName'] = ($result->getFacility()) ?  $result->getFacility()->getName() : '' ;
            $rg['website'] = $result->getWebsite();
            $tutors = [];
            foreach($result->getTutors()->toArray() as $tutor) {
                $tutors[] = $tutor->getSurname() . ': ' . $tutor->getFirstName();
                $ss .= $tutor->getSurname() . '|' . $tutor->getFirstName(). '|';
            }
            $rg['tutors'] = $tutors;
            $rg['SearchString'] = $ss;
            $content[] = $rg;
        }

        return $content;
    }

    /**
     * @var array
     */
    protected $searchDefinition = [
        'name',
        'nameShort',
        'tutors',
    ];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'name' => [
            'label' => [
                [
                    'label' => 'roll_group.name.label',
                ],
                [
                    'label' => 'roll_group.name_short.label',
                    'join' => '<br />',
                    'class' => 'small text-muted',
                ],
            ],
            'class' => 'text-center',
            'name' => 'name',
            'select' => ['r.name', 'r.nameShort'],
            'style' => 'combine',
            'options' => [
                'combine' => ['name' => [], 'nameShort' => ['class' => 'small text-muted', 'join' => '<br />']],
            ],

        ],
        'tutors' => [
            'label' => 'roll_group.tutors.label',
            'name' => 'tutors',
            'select' => false,
            'size' => 4,
            'style' => 'array',
            'options' => [
                'join' => '<br />',
            ],
        ],
        'facilityName' => [
            'label' => 'roll_group.facility.label',
            'name' => 'facilityName',
            'select' => 'f.name',
        ],
        'r.website' => [
            'label' => 'roll_group.website.label',
            'name' => 'website',
            'style' => 'text',
        ],
        'r.id' => [
            'label' => false,
            'name' => 'id',
            'display' => false,
        ],
    ];

    /**\
     * @var array
     */
    protected $actions = [
        'buttons' => [
            [
                'label' => 'school.roll_group.delete.title',
                'url' => '/school/roll/group/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
                'classMerge' => 'btn-sm',
            ],
            [
                'label' => 'school.roll_group.edit.title',
                'url' => '/school/roll/group/__id__/edit/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
                'classMerge' => 'btn-sm',
            ],
        ],
    ];
}
