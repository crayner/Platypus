<?php
namespace App\Form;

use App\Entity\Department;
use App\Form\Subscriber\DepartmentSubscriber;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{

	/**
	 * @var EntityManagerInterface
	 */
	private $ds;

    /**
     * DepartmentType constructor.
     * @param DepartmentSubscriber $ds
     */
	public function __construct(DepartmentSubscriber $ds)
	{
		$this->ds = $ds;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        $dept_id = $options['data'] instanceof Department ? $options['data']->getId() : 0;
		$builder
			->add('name', null,
				[
					'label' => 'department.name.label'
				]
			)
			->add('type', EnumType::class,
				[
					'label'        => 'department.type.label',
					'placeholder'  => 'department.type.placeholder',
				]
			)
			->add('nameShort', null,
				[
					'label' => 'department.name_short.label'
				]
			)
			->add('logo', ImageType::class,
				[
					'label'       => 'department.logo.label',
					'required'    => false,
					'attr'        => [
						'imageClass' => 'smallLogo'
					],
					'deletePhoto' => $options['deletePhoto'],
					'fileName'    => 'departmentLogo'
				]
			)
            ->add('blurb', CKEditorType::class,
                [
                    'label'    => 'department.blurb.label',
                    'attr'     => [
                        'rows' => 4,
                    ],
                    'required' => false,
                ]
            )
            ->add('subjectListing', TextType::class,
                [
                    'label'    => 'department.subject_listing.label',
                    'required' => false,
                ]
            )
        ;

		$builder->addEventSubscriber($this->ds);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'data_class'         => Department::class,
				'translation_domain' => 'School',
				'deletePhoto'        => null,
                'attr'               => [
                    'novalidate' => '',
                ],
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'department';
	}


}
