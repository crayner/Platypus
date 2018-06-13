<?php
namespace App\Form;

use App\Entity\SchoolYear;
use App\Entity\SchoolYearTerm;
use Hillrange\Form\Type\DateType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SchoolYearTermType extends AbstractType
{
	/**
	 * @var    EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * Construct
	 */
	public function __construct(EntityManagerInterface $manager)
	{
		$this->entityManager = $manager;
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$schoolYear  = $options['schoolYear'];
		$schoolYears = array();
		if (!is_null($schoolYear->getFirstDay()))
		{
			$schoolYears[] = $schoolYear->getFirstDay()->format('Y');
			if ($schoolYear->getFirstDay()->format('Y') !== $schoolYear->getLastDay()->format('Y'))
				$schoolYears[] = $schoolYear->getLastDay()->format('Y');
		}
		else
			$schoolYears[] = date('Y');
		$builder
			->add('name', TextType::class,
				array(
					'label' => 'term.name.label',
					'help' => 'term.name.help',
				)
			)
			->add('nameShort', TextType::class,
				array(
					'label' => 'term.name_short.label',
					'help' => 'term.name_short.help',
				)
			)
			->add('firstDay', DateType::class,
				array(
					'label' => 'term.first_day.label',
					'help' => 'term.first_day.help',
					'years' => $schoolYears,
				)
			)
			->add('lastDay', DateType::class,
				array(
					'label' => 'term.last_day.label',
					'help' => 'term.last_day.help',
					'years' => $schoolYears,
				)
			)
			->add('schoolYear', HiddenEntityType::class,
				[
					'class' => SchoolYear::class,
				]
			);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'data_class'         => SchoolYearTerm::class,
				'translation_domain' => 'SchoolYear',
				'error_bubbling'     => true,
			)
		);
		$resolver->setRequired(
		    [
		        'schoolYear',
            ]
        );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return $this->getName();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'school_year_term';
	}

	/**
	 * @param FormView      $view
	 * @param FormInterface $form
	 * @param array         $options
	 */
	public function buildView(FormView $view, FormInterface $form, array $options)
	{
        $view->vars['schoolYear'] = $options['schoolYear'];
	}
}
