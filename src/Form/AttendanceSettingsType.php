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
 * Date: 20/06/2018
 * Time: 11:36
 */
namespace App\Form;

use App\Entity\AttendanceCode;
use App\Form\Subscriber\MultipleSettingSubscriber;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AttendanceSettingsType
 * @package App\Form
 */
class AttendanceSettingsType extends AbstractType
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
            ->add('attendanceCodes', CollectionType::class,
                [
                    'entry_type' => AttendanceCodeType::class,
                    'entry_options' => [
                        'data_class' => AttendanceCode::class,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'redirect_route' => 'remove_attendance_code',
                    'button_merge_class'    => 'btn-sm',
                    'sort_manage' => true,
                ]
            )
            ->add('multipleSettings', SectionSettingType::class,
                [
                    'data' => $options['data']->getMultipleSettings(),
                ]
            )
        ;
    }
}