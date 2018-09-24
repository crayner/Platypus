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
 * Date: 21/09/2018
 * Time: 12:53
 */
namespace App\Form\Type;

use App\Entity\Course;
use App\Entity\CourseClass;
use App\Entity\Person;
use App\Entity\Scale;
use App\Form\Subscriber\CourseClassSubscriber;
use App\Util\SchoolYearHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\CollectionType;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\HiddenEntityType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CourseClassType
 * @package App\Form\Type
 */
class CourseClassType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $yearGroups = [];
        foreach($options['data']->getCourse()->getYearGroups() as $group)
            $yearGroups[] = $group->getId();

        $preferredStudents = $this->entityManager->getRepository(Person::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.primaryRole', 'r')
            ->where('r.category = :role')
            ->andWhere('p.status = :status')
            ->setParameter('status', 'full')
            ->setParameter('role', 'student')
            ->leftJoin('p.enrolments', 'e')
            ->leftJoin('e.rollGroup', 'rg')
            ->leftJoin('e.yearGroup', 'y')
            ->andWhere('y.id in (:yearGroups)')
            ->setParameter('yearGroups', $yearGroups, Connection::PARAM_INT_ARRAY)
            ->leftJoin('rg.schoolYear', 'sy')
            ->andWhere('sy = :currentYear')
            ->setParameter('currentYear', SchoolYearHelper::getCurrentSchoolYear())
            ->getQuery()
            ->getResult();

        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Class Name',
                    'help' => 'Displayed as "%{name}"',
                    'help_params' => ['%{name}' => $options['data']->getName()]
                ]
            )
            ->add('nameShort', TextType::class,
                [
                    'label' => 'Class Short Name',
                    'help' => 'Displayed as "%{name}"',
                    'help_params' => ['%{name}' => $options['data']->getNameShort()]
                ]
            )
            ->add('useCourseName', ToggleType::class,
                [
                    'label' => 'Use Course Name',
                    'help' => 'Prefix the class name with the course name.'
                ]
            )
            ->add('reportable', ToggleType::class,
                [
                    'label' => 'Reportable?',
                    'help' => 'Should this class show in reports?'
                ]
            )
            ->add('attendance', ToggleType::class,
                [
                    'label' => 'Track Attendance?',
                    'help' => 'Should this class allow attendance to be taken?'
                ]
            )
            ->add('useScale', EntityType::class,
                [
                    'label' => 'Use Scale',
                    'help' => 'Default scale used for this class.',
                    'class' => Scale::class,
                    'choice_label' => 'name',
                ]
            )
            ->add('course', HiddenEntityType::class,
                [
                    'class' => Course::class,
                ]
            )
            ->add('students', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'choice_label' => 'rollGroupFullName',
                        'preferred_choices' => $preferredStudents,
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->orderBy('rg.name', 'ASC')
                                ->addOrderBy('p.surname', 'ASC')
                                ->addOrderBy('p.firstName', 'ASC')
                                ->leftJoin('p.primaryRole', 'r')
                                ->where('r.category = :role')
                                ->andWhere('p.status = :status')
                                ->setParameter('status', 'full')
                                ->setParameter('role', 'student')
                                ->leftJoin('p.enrolments', 'e')
                                ->leftJoin('e.rollGroup', 'rg')
                                ->leftJoin('rg.schoolYear', 'sy')
                                ->andWhere('sy = :currentYear')
                                ->setParameter('currentYear', SchoolYearHelper::getCurrentSchoolYear())
                                ;
                        },
                    ],
                ]
            )
            ->add('tutors', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_options' => [
                        'choice_label' => 'fullName',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->orderBy('p.surname', 'ASC')
                                ->addOrderBy('p.firstName', 'ASC')
                                ->leftJoin('p.primaryRole', 'r')
                                ->where('r.category <> :category')
                                ->andWhere('p.status = :status')
                                ->setParameter('status', 'full')
                                ->setParameter('category', 'student')
                            ;
                        },
                    ],
                ]
            )
            ->add('former', CollectionType::class,
                [
                    'entry_type' => ClassParticipantType::class,
                    'button_merge_class' => 'btn-sm',
                    'allow_add' => false,
                    'allow_delete' => false,
                    'entry_options' => [
                        'choice_label' => 'fullName',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->orderBy('p.surname', 'ASC')
                                ->addOrderBy('p.firstName', 'ASC')
                                ->andWhere('p.status != :status')
                                ->setParameter('status', 'full')
                            ;
                        },
                    ],
                ]
            )
            ->addEventSubscriber(new CourseClassSubscriber())
        ;

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
                'translation_domain' => 'Course',
                'data_class' => CourseClass::class,
            ]
        );
    }

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CourseClassType constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}