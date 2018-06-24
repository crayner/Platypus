<?php
namespace App\Form;

use App\Entity\Department;
use App\Entity\DepartmentStaff;
use App\Entity\Person;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\HiddenEntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentStaffType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('member', EntityType::class,
				[
					'label'         => 'department.member.member.label',
					'class'         => Person::class,
					'choice_label'  => 'fullName',
					'query_builder' => function (EntityRepository $er) {
						return $er->createQueryBuilder('s')
							->orderBy('s.surname', 'ASC')
							->addOrderBy('s.firstName', 'ASC');
					},
					'placeholder'   => 'department.member.member.placeholder',
					'help' => 'department.member.member.help',
				]
			)
			->add('role', EnumType::class,
				[
					'label'        => 'department.member.role.label',
					'placeholder'  => 'department.member.role.placeholder',
				]
			)
			->add('department', HiddenEntityType::class,
				[
					'class' => Department::class,
				]
			)
        ;

	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class'         => DepartmentStaff::class,
			'translation_domain' => 'School',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'department_member';
	}
}
