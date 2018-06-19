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
 * Date: 18/06/2018
 * Time: 18:31
 */
namespace App\Form;

use App\Entity\StudentNoteCategory;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class StudentsSettingsType
 * @package App\Form
 */
class StudentsSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', CollectionType::class,
                [
                    'entry_type' => StudentNoteCategoryType::class,
                    'entry_options' => [
                        'data_class' => StudentNoteCategory::class,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'remove_element_route' => 'remove_student_note_category',
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