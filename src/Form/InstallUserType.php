<?php
namespace App\Form;

use App\Manager\SettingManager;
use App\Validator\GoogleOAuth;
use Hillrange\Form\Type\TextType;
use App\Organism\User;
use Hillrange\Security\Validator\Password;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class InstallUserType extends AbstractType
{
    /**
     * @var SettingManager 
     */
    private $settingManager;

    /**
     * UserType constructor.
     */
    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    /**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('_email', EmailType::class,
				[
					'label'       => 'person.email.label',
					'help' => 'person.email.help',
                    'translation_domain' => 'Person',
					'constraints' => [
						new NotBlank(),
						new Email(
							[
								'strict'  => true,
								'checkMX' => true,
							]
						),
					],
				]
			)
			->add('_username', TextType::class,
                            [
                                'label'       => 'user.username.label',
                                'help' => 'user.username.help',
                                'required'    => false,
                                'constraints' => [
                                    new NotBlank(),
                                ],
                            ]
                        )
            ->add('_password', TextType::class,
                [
                    'label'       => 'user.password.label',
                    'help' => 'user.password.help',
                    'constraints' => [
                        new NotBlank(),
                        new Password(),
                    ],
                ]
            )
            ->add('surname', TextType::class,
                [
                    'label' => 'person.surname.label',
                    'translation_domain' => 'Person',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('firstName', TextType::class,
                [
                    'label' => 'person.firstName.label',
                    'translation_domain' => 'Person',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('orgName', TextType::class,
                [
                    'label' => 'system.org_name.label',
                    'data' => $this->settingManager->get('org.name.long', 'Busybee Learning'),
                    'translation_domain' => 'System',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ]
            )
            ->add('orgCode', TextType::class,
                [
                    'label' => 'system.org_code.label',
                    'translation_domain' => 'System',
                    'data' => $this->settingManager->get('org.name.short', 'BEE'),
                    'constraints' => [
                        new Length(['max' => 5]),
                        new NotBlank(),
                    ],
                ]
            )
            ->add('title', SettingChoiceType::class,
                [
                    'label' => 'person.honorific.label',
                    'translation_domain' => 'Person',
                    'setting_name' => 'person.title.list',
                    'placeholder' => 'person.honorific.placeholder',
                    'required' => false,
                ]
            )
            ->add('currency', CurrencyType::class,
                [
                    'label' => 'system.currency.label',
                    'help' => 'system.currency.help',
                    'placeholder' => 'system.placeholder',
                    'data' => $this->settingManager->get('currency'),
                    'translation_domain' => 'System',
                    'constraints' => [
                        new NotBlank(),
                        new Currency(),
                    ],
                ]
            )
            ->add('country', CountryType::class,
                [
                    'label' => 'system.country.label',
                    'help' => 'system.country.help',
                    'placeholder' => 'system.placeholder',
                    'data' => $this->settingManager->getParameter('country'. 'AU'),
                    'translation_domain' => 'System',
                    'constraints' => [
                        new NotBlank(),
                        new Country(),
                    ],
                ]
            )
            ->add('timezone', TimezoneType::class,
                [
                    'label' => 'system.timezone.label',
                    'help' => 'system.timezone.help',
                    'placeholder' => 'system.placeholder',
                    'data' => $this->settingManager->getParameter('timezone', 'Australia/Sydney'),
                    'translation_domain' => 'System',
                    'constraints' => [
                        new NotBlank(),
                    ],
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
				'translation_domain' => 'System',
				'data_class'         => User::class,
                'constraints'   => [
                    new GoogleOAuth(),
                ],
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'install_user';
	}


}
