<?php
namespace App\Form;

use Hillrange\Form\Type\TextType;
use App\Organism\Database;
use Hillrange\Form\Type\ToggleType;
use Hillrange\Form\Validator\NoWhiteSpace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallDatabaseType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('driver', ChoiceType::class,
				[
					'label'       => 'database.driver.label',
					'mapped'      => false,
					'choices'     => [
						'database.driver.mysql' => 'pdo_mysql',
					],
					'help' => 'database.driver.help',
					'constraints' => [
						new NotBlank(),
					],
				]
			)
			->add('port', HiddenType::class,
				[
					'label' => 'database.port.label',
					'help' => 'database.port.help',
					'constraints' => [
						new NotBlank(),
					],
				]
			)
			->add('host', TextType::class,
				[
					'label'       => 'database.host.label',
					'help' => 'database.host.help',
					'constraints' => [
						new NotBlank(),
					],
				]
			)
			->add('name', TextType::class,
				[
					'label'       => 'database.name.label',
					'help' => 'database.name.help',
					'constraints' => [
						new NotBlank(),
						new NoWhiteSpace(
							['repair' => false,]
						),
					],
				]
			)
			->add('user', TextType::class,
				[
					'label'       => 'database.user.label',
						'help' => 'database.user.help',
					'constraints' => [
						new NotBlank(),
						new NoWhiteSpace(
							['repair' => false,]
						),
					],
				]
			)
			->add('pass', TextType::class,
				[
					'label'       => 'database.pass.label',
					'help' => 'database.pass.help',
					'constraints' => [
						new NotBlank(),
					],
				]
			)
			->add('prefix', TextType::class,
				[
					'label'    => 'database.prefix.label',
					'help' => 'database.prefix.help',
					'required' => false,
                    'constraints' => [
                        new Length(['max' => 5]),
                    ],

                ]
			)
            ->add('charset', ChoiceType::class,
                [
                    'label'    => 'database.charset.label',
                    'help' => 'database.charset.help',
                    'required' => true,
                    'choices'   => [
                        'database.charset.utf8' => 'utf8',
                        'database.charset.utf8mb4' => 'utf8mb4',
                    ],
                ]
            )
            ->add('demoData', ToggleType::class,
                [
                    'label'    => 'database.demo_data.label',
                    'help' => 'database.demo_data.help',
                    'required' => true,
                ]
            )
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			[
				'translation_domain' => 'Installer',
				'data_class'         => Database::class,
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'install_database';
	}
}
