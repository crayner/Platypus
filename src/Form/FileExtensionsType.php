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
 * Time: 11:49
 */
namespace App\Form;

use App\Entity\FileExtension;
use Hillrange\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FileExtensionsType
 * @package App\Form
 */
class FileExtensionsType extends AbstractType
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
            ->add('fileExtensions', CollectionType::class,
                [
                    'entry_type' => FileExtensionType::class,
                    'entry_options' => [
                        'data_class' => FileExtension::class,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'button_merge_class' => 'btn-sm',
                    'redirect_route' => 'remove_file_extension',
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
        $resolver->setDefaults([
            'translation_domain' => 'System',
        ]);
    }
}
