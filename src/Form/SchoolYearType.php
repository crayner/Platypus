<?php
namespace App\Form;

use App\Entity\SchoolYear;
use App\Form\Subscriber\SchoolYearSubscriber;
use App\Validator\SchoolYearDate;
use App\Validator\SchoolYearStatus;
use App\Validator\SpecialDayDate;
use App\Validator\TermDate;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\DateType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolYearType extends AbstractType
{
	/**
	 * @var SchoolYearSubscriber
	 */
	private $schoolYearSubscriber;

	/**
	 * CalendarType constructor.
	 *
	 * @param SchoolYearSubscriber $SchoolYearSubscriber
	 */
	public function __construct(SchoolYearSubscriber $schoolYearSubscriber)
	{
		$this->schoolYearSubscriber = $schoolYearSubscriber;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class,
				[
					'label' => 'Name',
				]
			)
            ->add('firstDay', DateType::class,
                [
                    'label' => 'First Day',
                ]
            )
            ->add('sequence', HiddenType::class)
			->add('lastDay', DateType::class,
				array(
					'label'       => 'Last Day',
				)
			)
			->add('status', EnumType::class,
				array(
					'label'         => 'Status',
					'constraints'   => [
						new SchoolYearStatus(array('id' => is_null($options['data']->getId()) ? 'Add' : $options['data']->getId())),
					],
                    'disabled' => true,
				)
			)
			->add('terms', CollectionType::class, array(
					'entry_type'    => SchoolYearTermType::class,
					'allow_add'     => true,
					'entry_options' => array(
						'schoolYear' => $options['data'],
					),
					'constraints'   => array(
						new TermDate(['schoolYear' => $options['data']]),
					),
					'label'         => false,
					'by_reference'  => false,
                    'route'         => 'term_manage',
                    'button_merge_class' => 'btn-sm',
				)
			)
			->add('specialDays', CollectionType::class, array(
					'entry_type'    => SchoolYearSpecialDayType::class,
					'allow_add'     => true,
					'entry_options' => array(
						'schoolYear' => $options['data'],
					),
					'constraints'   => array(
						new SpecialDayDate(['schoolYear' => $options['data']]),
					),
					'label'         => false,
					'allow_delete'  => true,
					'by_reference'  => false,
                    'route'         => 'special_day_manage',
                    'button_merge_class' => 'btn-sm',
				)
			)
        ;

		$builder->addEventSubscriber($this->schoolYearSubscriber);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'data_class'         => SchoolYear::class,
				'translation_domain' => 'SchoolYear',
                'attr' => [
                    'novalidate' => null,
                ],
                'constraints' => array(
                    new SchoolYearDate(),
                ),
			)
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'school_year';
	}


}
