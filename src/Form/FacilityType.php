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
 * Date: 22/06/2018
 * Time: 14:44
 */
namespace App\Form;

use App\Entity\Facility;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FacilityType
 * @package App\Form
 */
class FacilityType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                    'label' => 'facility.name.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('type', SettingChoiceType::class, array(
                    'label'       => 'facility.type.label',
                    'placeholder' => 'facility.type.placeholder',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                    'setting_name' => 'school_admin.facility_types',
                    'sort_choice' => false,
                )
            )
            ->add('capacity', IntegerType::class, array(
                    'label'      => 'facility.capacity.label',
                    'attr'       => array(
                        'min'   => '0',
                        'max'   => '9999',
                        'class' => 'monitorChange',
                    ),
                    'help'  => 'facility.capacity.help',
                    'empty_data' => '0',
                )
            )
            ->add('computer', ToggleType::class, array(
                    'label' => 'facility.computer.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('studentComputers', IntegerType::class, array(
                    'label'      => 'facility.studentComputers.label',
                    'attr'       => array(
                        'min'   => '0',
                        'max'   => '999',
                        'class' => 'monitorChange',
                    ),
                    'empty_data' => '0',
                )
            )
            ->add('projector', ToggleType::class, array(
                    'label' => 'facility.projector.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('tv', ToggleType::class, array(
                    'label' => 'facility.tv.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('dvd', ToggleType::class, array(
                    'label' => 'facility.dvd.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('hifi', ToggleType::class, array(
                    'label' => 'facility.hifi.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('speakers', ToggleType::class, array(
                    'label' => 'facility.speakers.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('iwb', ToggleType::class, array(
                    'label' => 'facility.iwb.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('duplicateid', HiddenType::class, [
                    'mapped' => false,
                ]
            )
            ->add('phoneInt', null, array(
                    'label' => 'facility.phoneint.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('phoneExt', null, array(
                    'label' => 'facility.phoneext.label',
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
            )
            ->add('comment', TextareaType::class, array(
                    'label'    => 'facility.comment.label',
                    'required' => false,
                    'attr'     => [
                        'class' => 'monitorChange',
                    ],
                )
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
                'data_class'         => Facility::class,
                'translation_domain' => 'School',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'facility';
    }
}
