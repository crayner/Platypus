<?php
/**
 * Created by PhpStorm.
 *
 * This file is part of the Busybee Project.
 *
 * (c) Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 24/08/2018
 * Time: 08:56
 */
namespace App\Form\Type;

use App\Entity\House;
use App\Entity\Person;
use App\Entity\PersonRole;
use App\Entity\SchoolYear;
use App\Form\SettingChoiceType;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\DateType;
use Hillrange\Form\Type\DocumentType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Hillrange\Security\Util\ParameterInjector;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PersonType
 * @package App\Form\Type
 */
class PersonType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = [];
        $year = date('Y');
        for($i=3; $i<120; $i++)
            $years[] = $year - $i;
        $builder
            ->add('surname', TextType::class,
                [
                    'label' => 'person.surname.label',
                    'help' => 'person.surname.help',
                ]
            )
            ->add('firstName', TextType::class,
                [
                    'label' => 'person.first_name.label',
                    'help' => 'person.first_name.help',
                ]
            )
            ->add('title', EnumType::class,
                [
                    'label' => 'person.title.label',
                    'required' => false,
                ]
            )
            ->add('gender', EnumType::class,
                [
                    'label' => 'person.gender.label',
                    'required' => false,
                ]
            )
            ->add('preferredName', TextType::class,
                [
                    'label' => 'person.preferred_name.label',
                    'help' => 'person.preferred_name.help',
                ]
            )
            ->add('officialName', TextType::class,
                [
                    'label' => 'person.official_name.label',
                    'help' => 'person.official_name.help',
                ]
            )
            ->add('nameInCharacters', TextType::class,
                [
                    'label' => 'person.name_in_characters.label',
                    'help' => 'person.name_in_characters.help',
                ]
            )
            ->add('dob', DateType::class,
                [
                    'label' => 'person.dob.label',
                    'help' => 'person.dob.help',
                    'years' => $years,
                    'required' => false,
                ]
            )
            ->add('photo', ImageType::class,
                [
                    'attr'        => array(
                        'imageClass' => 'headShot75 img-thumbnail',
                    ),
                    'help'       => 'person.photo.help',
                    'label'       => 'person.photo.label',
                    'required'    => false,
                    'deletePhoto' => ['personal_image_remover', ['id' => $options['data']->getId(), 'getImageMethod' => 'getPhoto', 'tabName' => 'basic.information']],
                    'fileName'    => 'person_' . $options['data']->getId(),
                ]
            )
            ->add('primaryRole', EntityType::class,
                [
                    'help'       => 'person.primary_role.help',
                    'label'       => 'person.primary_role.label',
                    'placeholder'       => 'person.primary_role.placeholder',
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('secondaryRoles', EntityType::class,
                [
                    'help'       => 'person.secondary_roles.help',
                    'label'       => 'person.secondary_roles.label',
                    'class' => PersonRole::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                ]
            )
            ->add('status', EnumType::class,
                [
                    'help'       => 'person.status.help',
                    'label'       => 'person.status.label',
                ]
            )
            ->add('email', EmailType::class,
                [
                    'label' => 'person.email.label',
                    'required' => false,
                ]
            )
            ->add('emailAlternate', EmailType::class,
                [
                    'label' => 'person.email.label',
                    'required' => false,
                ]
            )
            ->add('languageFirst', LanguageType::class,
                [
                    'label'       => 'person.language_first.label',
                    'placeholder'       => 'person.language_first.placeholder',
                    'required' => false,
                ]
            )
            ->add('languageSecond', LanguageType::class,
                [
                    'label'       => 'person.language_second.label',
                    'placeholder'       => 'person.language_second.placeholder',
                    'required' => false,
                ]
            )
            ->add('languageThird', LanguageType::class,
                [
                    'label'       => 'person.language_third.label',
                    'placeholder'       => 'person.language_third.placeholder',
                    'required' => false,
                ]
            )
            ->add('countryOfBirth', CountryType::class,
                [
                    'label'       => 'person.country_birth.label',
                    'placeholder'       => 'person.country_birth.placeholder',
                    'required' => false,
                ]
            )
            ->add('birthCertificateScan', DocumentType::class,
                [
                    'label'       => 'person.birth_certificate_scan.label',
                    'help'       => 'person.birth_certificate_scan.help',
                    'required' => false,
                    'fileName' => $options['data']->getShortName().'_bc',
                    'deletePhoto' => ['personal_image_remover', ['id' => $options['data']->getId(), 'getImageMethod' => 'getBirthCertificateScan', 'tabName' => 'background.information']],
                ]
            )
            ->add('ethnicity', SettingChoiceType::class,
                array(
                    'label'        => 'student.ethnicity.label',
                    'placeholder'  => 'student.ethnicity.placeholder',
                    'required'     => false,
                    'setting_name' => 'ethnicity.list',
                    'translation_domain' => 'Person',
                    'translation_prefix' => false,
                )
            )
            ->add('religion', SettingChoiceType::class,
                array(
                    'label'        => 'student.religion.label',
                    'placeholder'  => 'student.religion.placeholder',
                    'required'     => false,
                    'setting_name' => 'religion.list',
                    'translation_prefix'    => false,
                    'strict_validation' => false,
                    'translation_domain' => 'Person',
                )
            )
            ->add('citizenship1', CountryType::class,
                array(
                    'label'        => 'person.citizenship_1.label',
                    'placeholder'  => 'person.citizenship.placeholder',
                    'required'     => false,
                )
            )
            ->add('citizenship1Passport', TextType::class,
                array(
                    'label'        => 'person.citizenshipship_1_passport.label',
                    'required'     => false,
                )
            )
            ->add('citizenship1PassportScan', DocumentType::class,
                array(
                    'label'        => 'person.citizenship_1_passport_scan.label',
                    'help'        => 'person.citizenship_1_passport_scan.help',
                    'required'     => false,
                    'fileName' => $options['data']->getShortName().'_ps',
                    'deletePhoto' => ['personal_image_remover', ['id' => $options['data']->getId(), 'getImageMethod' => 'getCitizenship1PassportScan', 'tabName' => 'background.information']],
                )
            )
            ->add('citizenship2', CountryType::class,
                array(
                    'label'        => 'person.citizenship_2.label',
                    'placeholder'  => 'person.citizenship.placeholder',
                    'required'     => false,
                )
            )
            ->add('citizenship2Passport', TextType::class,
                array(
                    'label'        => 'person.citizenship_2_passport.label',
                    'required'     => false,
                )
            )
            ->add('nationalIDCard', TextType::class,
                array(
                    'label'        => ['person.national_id_card.label', ['%{national}' => $this->country]],
                    'required'     => false,
                )
            )
            ->add('nationalIDCardScan', DocumentType::class,
                array(
                    'label'        => ['person.national_id_card_scan.label', ['%{national}' => $this->country]],
                    'help'        => 'person.national_id_card_scan.help',
                    'required'     => false,
                    'fileName' => $options['data']->getShortName().'_n_id',
                    'deletePhoto' => ['personal_image_remover', ['id' => $options['data']->getId(), 'getImageMethod' => 'getNationalIDCardScan', 'tabName' => 'background.information']],
                )
            )
            ->add('residencyStatus', SettingChoiceType::class,
                [
                    'label'        => ['person.residency_status.label', ['%{national}' => $this->country]],
                    'placeholder'  => 'person.residency_status.placeholder',
                    'required'     => false,
                    'setting_name' => 'person.residency_status',
                    'translation_domain' => 'Person',
                ]
            )
            ->add('visaExpiryDate', DateType::class,
                array(
                    'label'        => ['person.visa_expiry_date.label', ['%{national}' => $this->country]],
                    'help'  => 'person.visa_expiry_date.help',
                    'required'     => false,
                )
            )
            ->add('house', EntityType::class,
                [
                    'label'       => 'person.house.label',
                    'placeholder'       => 'person.house.placeholder',
                    'class' => House::class,
                    'choice_label' => 'name',
                    'required' => false,
                ]
            )
            ->add('vehicleRegistration', TextType::class,
                array(
                    'label'        => 'person.vehicle_registration.label',
                    'required'     => false,
                )
            )
            ->add('website', UrlType::class,
                array(
                    'label'        => 'person.website.label',
                    'required'     => false,
                )
            )
        ;

        if ($options['manager']->isStudent())
            $this->buildStudentForm($builder, $options);
        if ($options['manager']->isStaff())
            $this->buildStaffForm($builder, $options);
        if ($options['manager']->isParent())
            $this->buildParentForm($builder, $options);
        $this->buildUserForm($builder, $options);
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => Person::class,
                'translation_domain' => 'Person',
                'attr' => [
                    'noValidate' => true,
                ],
                'allow_extra_fields' => true,
            ]
        );
        $resolver->setRequired(
            [
                'manager',
            ]
        );
    }

    /**
     * getBlockPrefix
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'person';
    }

    /**
     * @var null|string
     */
    private $country;

    /**
     * PersonType constructor.
     * @param ParameterInjector $injector
     */
    public function __construct(ParameterInjector $injector)
    {
        $country = $injector->getParameter('country');
        $this->country = Intl::getRegionBundle()->getCountryName($country);
    }

    /**
     * buildStudentForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    private function buildStudentForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('studentIdentifier', TextType::class,
                array(
                    'label'        => 'person.student_identifier.label',
                    'help'        => 'person.student_identifier.help',
                    'required'     => false,
                )
            )
            ->add('lockerNumber', TextType::class,
                array(
                    'label'        => 'person.locker_number.label',
                    'required'     => false,
                )
            )
            ->add('lastSchool', TextType::class,
                [
                    'label'       => 'person.last_school.label',
                    'required' => false,
                ]
            )
            ->add('nextSchool', TextType::class,
                [
                    'label'       => 'person.next_school.label',
                    'required' => false,
                ]
            )
            ->add('departureReason', TextType::class,
                [
                    'label'       => 'person.departure_reason.label',
                    'required' => false,
                ]
            )
            ->add('dateStart', DateType::class,
                [
                    'required' => false,
                    'label'       => 'person.date_start.label',
                    'help'       => 'person.date_start.help',
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
            ->add('dateEnd', DateType::class,
                [
                    'label'       => 'person.date_end.label',
                    'help'       => 'person.date_end.help',
                    'required' => false,
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
            ->add('graduationYear', EntityType::class,
                [
                    'label'       => 'person.graduation_year.label',
                    'help'       => 'person.graduation_year.help',
                    'placeholder'       => 'person.graduation_year.placeholder',
                    'class' => SchoolYear::class,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.firstDay', 'DESC');
                    },
                    'required' => false,
                ]
            )
            ->add('transport', TextType::class,
                [
                    'label'       => 'person.transport.label',
                    'required' => false,
                ]
            )
            ->add('transportNotes', TextareaType::class,
                [
                    'label'       => 'person.transport_notes.label',
                    'required' => false,
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
        ;
    }

    /**
     * buildStaffForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    private function buildStaffForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('lockerNumber', TextType::class,
                array(
                    'label'        => 'person.locker_number.label',
                    'required'     => false,
                )
            )
            ->add('lastSchool', TextType::class,
                [
                    'label'       => 'person.last_school.label',
                    'required' => false,
                ]
            )
            ->add('nextSchool', TextType::class,
                [
                    'label'       => 'person.next_school.label',
                    'required' => false,
                ]
            )
            ->add('departureReason', TextType::class,
                [
                    'label'       => 'person.departure_reason.label',
                    'required' => false,
                ]
            )
            ->add('dateStart', DateType::class,
                [
                    'required' => false,
                    'label'       => 'person.date_start.label',
                    'help'       => 'staff.date_start.help',
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
            ->add('dateEnd', DateType::class,
                [
                    'label'       => 'person.date_end.label',
                    'help'       => 'staff.date_end.help',
                    'required' => false,
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
            ->add('transport', TextType::class,
                [
                    'label'       => 'person.transport.label',
                    'required' => false,
                ]
            )
            ->add('transportNotes', TextareaType::class,
                [
                    'label'       => 'person.transport_notes.label',
                    'required' => false,
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
        ;
    }

    /**
     * buildUserForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    private function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('canLogin', ToggleType::class,
                [
                    'label'       => 'person.can_login.label',
                ]
            )
            ->add('forcePasswordReset', ToggleType::class,
                [
                    'help'       => 'person.force_password_reset.help',
                    'label'       => 'person.force_password_reset.label',
                ]
            )
            ->add('username', TextType::class,
                [
                    'help'       => 'person.username.help',
                    'label'       => 'person.username.label',
                ]
            )
        ;
    }

    /**
     * buildParentForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    private function buildParentForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profession', TextType::class,
                array(
                    'label'        => 'person.profession.label',
                    'required'     => false,
                )
            )
            ->add('employer', TextType::class,
                array(
                    'label'        => 'person.employer.label',
                    'required'     => false,
                )
            )
            ->add('jobTitle', TextType::class,
                array(
                    'label'        => 'person.job_title.label',
                    'required'     => false,
                )
            )
            ->add('dateStart', DateType::class,
                [
                    'required' => false,
                    'label'       => 'person.date_start.label',
                    'help'       => 'parent.date_start.help',
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
            ->add('dateEnd', DateType::class,
                [
                    'label'       => 'person.date_end.label',
                    'help'       => 'parent.date_end.help',
                    'required' => false,
                    'years' => range(date('Y', strtotime('-4 years')),date('Y', strtotime('-20 years')), -1),
                ]
            )
        ;
    }
}