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
        'userId' => 'u.id',
        'p.status',
        'r.name',
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
		];

	/**
	 * @var array
	 */
	protected $choices = [
		'all'     => [
			'route'  => 'person_manage',
			'prompt' => 'person.pagination.all',
		],
		'student' => [
			'route'  => 'student_manage',
			'prompt' => 'person.pagination.student',
		],
		'staff'   => [
			'route'  => 'staff_manage',
			'prompt' => 'person.pagination.staff',
		],
		'contact' => [
			'route'  => 'contact_manage',
			'prompt' => 'person.pagination.contact',
		],
		'user'    => [
			'route'  => 'user_manage',
			'prompt' => 'person.pagination.user',
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
        'p.status' => [
            'label' => 'person.status.label',
            'name' => 'status',
            'size' => 1,
            'translate' => 'person.status.',
            'class' => 'small text-center',
        ],
        'r.name' => [
            'label' => 'person.primary_role.label',
            'name' => 'name',
            'size' => 1,
            'class' => 'small text-center',
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
}