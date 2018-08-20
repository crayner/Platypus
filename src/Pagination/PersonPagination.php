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
			'.surname',
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

}