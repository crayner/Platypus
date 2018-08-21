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
		'person.surname.label'   => [
			'surname',
			'firstName',
		],
		'person.firstName.label' => [
			'firstName',
			'surname',
		],
		'person.email.label'     => [
			'email',
			'surname',
			'firstName',
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
        'p.title',
		'p.surname',
		'p.firstName',
        'p.email',
		'p.id',
        'u.id AS userId',
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
//			'p.phones' => [
//				'type' => 'leftJoin',
//				'alias' =>'ph',
//			],
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
        'p.title',
        'p.surname',
        'p.firstName',
        'p.email',
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
            'options' => [
                'width' => '75',
                'default' => 'build/static/images/DefaultPerson.png'
            ],
        ],
        'p.title' => [
            'label' => 'person.title.label',
            'name' => 'title',
            'translate' => 'person.title.',
            'size' => 1,
            'class' => 'small',
        ],
        'p.surname' => [
            'label' => 'person.surname.label',
            'name' => 'surname',
        ],
        'p.firstName' => [
            'label' => 'person.firstName.label',
            'name' => 'firstName',
        ],
        'p.email' => [
            'label' => 'person.email.label',
            'name' => 'email',
        ],
        'p.id' => [
            'label' => false,
            'display' => false,
            'name' => 'id',
        ],
        'u.id AS userId' => [
            'label' => false,
            'display' => false,
            'name' => 'userId',
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
        return $this;
    }
}