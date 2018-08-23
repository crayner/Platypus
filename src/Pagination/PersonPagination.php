<?php
namespace App\Pagination;

use App\Entity\Person;

class PersonPagination extends PaginationReactManager
{
	/**
	 * @var string
	 */
	protected $name = 'Person';

	/**
	 * @var string
	 */
	protected $alias = 'p';

	/**
	 * @var array
	 */
	protected $sortByList = [
		'person.full_name.label' => [
			'fullName',
		],
	];
	/**
	 * @var int
	 */
	protected $limit = 25;

	/**
	 * @var array
	 */
	protected $select = [
        'p.photo',
		'fullName' => "CONCAT(p.surname, ': ', p.firstName)",
		'p.id',
        'u.username',
        'userId' => 'u.id',
        'details' => ['p.status', 'r.name AS primaryRole', 'f.name AS familyName'],
	];

	/**
	 * @var string
	 */
	protected $entityName = Person::class;

	/**
	 * @var array
	 */
	protected $join =
		[
			'p.user' => [
				'type'  => 'leftJoin',
				'alias' => 'u',
			],
			'p.primaryRole' => [
				'type' => 'leftJoin',
				'alias' =>'r',
			],
            'p.families' => [
                'type' => 'leftJoin',
                'alias' => 'fp',
            ],
            'fp.family' => [
                'type' => 'leftJoin',
                'alias' => 'f',
            ],
		];

	/**
	 * @var string
	 */
	protected $transDomain = 'Person';

    /**
     * @var array|null
     */
    protected $searchDefinition = [
        'p.surname',
        'p.firstName',
        'f.name',
    ];

    /**
     * getAllResults
     *
     * @return array
     */
    public function getAllResults(): array
    {
        return $this->buildQuery()
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @var array
     */
    protected $headerDefinition = [
        'title' => 'person.pagination.title',
    ];

    /**
     * @var array
     */
    protected $columnDefinitions = [
        'p.photo' => [
            'label' => 'person.photo.label',
            'name' => 'photo',
            'style' => 'photo',
            'class' => 'text-center',
            'options' => [
                'width' => '75',
                'default' => 'build/static/images/DefaultPerson.png'
            ],
        ],
        'fullName' => [
            'label' => 'person.full_name.label',
            'name' => 'fullName',
            'size' => 3,
        ],
        'p.id' => [
            'label' => false,
            'display' => false,
            'name' => 'id',
        ],
        'userId' => [
            'label' => false,
            'display' => false,
            'name' => 'userId',
        ],
        'details' => [
            'label' => 'person.details.label',
            'name' => 'details',
            'style' => 'combine',
            'translate' => [
                'status' => 'person.status.',
            ],
            'options' => [
                'combine' => ['status', 'primaryRole', 'familyName'],
                'join' => 'nl',
            ],
            'class' => 'text-center',
        ],
        'u.username' => [
            'label' => 'person.user_name.label',
            'name' => 'username',

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

        foreach(Person::getTitleList() as $title)
            $this->specificTranslations[] = 'person.title.' . $title;
        foreach(Person::getStatusList() as $value)
            $this->specificTranslations[] = 'person.status.' . $value;
        return $this;
    }

    /**\
     * @var array
     */
    protected $actions = [
        'size' => 3,
        'buttons' => [
            [
                'label' => 'person.action.password.change',
                'url' => '/person/__id__/password/change/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'icon' => ['fas', 'unlock-alt'],
                'colour' => 'light',
            ],
            [
                'label' => 'person.action.delete',
                'url' => '/person/__id__/delete/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'delete',
            ],
            [
                'label' => 'person.action.edit',
                'url' => '/person/__id__/edit/',
                'url_options' => [
                    '__id__' => 'id',
                ],
                'type' => 'edit',
                'response_type' => 'redirect',
            ],
        ],
    ];
}