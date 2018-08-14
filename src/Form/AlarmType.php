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
 * Date: 4/08/2018
 * Time: 09:38
 */
namespace App\Form;

use App\Entity\Alarm;
use Hillrange\Form\Type\EnumType;
use Hillrange\Form\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class AlarmType
 * @package App\Form
 */
class AlarmType extends AbstractType
{
    /**
     * buildForm
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', EnumType::class,
                [
                    'label' => 'alarm.type.label',
                    'help' => 'alarm.type.help',
                ]
            )
            ->add('customAlarm', FileType::class,
                [
                    'mapped' => false,
                    'label' => 'alarm.custom_alarm.label',
                    'help' => 'alarm.custom_alarm.help',
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => ['audio/mp3', 'audio/mpeg', 'audio/vnd.wav', 'audio/mp4']
                        ]),
                    ],
                    'fileName' => 'customAlarm',

                ]
            )
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
                'translation_domain' => 'System',
                'data_class' => Alarm::class,
            ]
        );
    }
}