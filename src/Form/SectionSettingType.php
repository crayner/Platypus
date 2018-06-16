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
 * Date: 16/06/2018
 * Time: 14:47
 */
namespace App\Form;

use App\Entity\Setting;
use App\Organism\SettingCache;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SectionSettingType
 * @package App\Form
 */
class SectionSettingType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];

        foreach($data->getSections() as $q=>$section)
        {
            $collectionManager = $data->getNewCollection($section);

            $builder->add(strtolower(str_replace([' '], '_', trim($section['name']))),CollectionType::class,
                [
                    'allow_up' => false,
                    'allow_down' => false,
                    'data' => $collectionManager,
                    'entry_type' => MultipleSettingType::class,
                    'entry_options_data_class' => SettingCache::class,
                    'entry_options' => [
                        'all_data' => $collectionManager->getCollection(),
                        'section_name' => $section['name'],
                        'section_description' => $section['description'],
                    ],
                ]
            );
        }
    }

    public function getBlockPrefix()
    {
        return 'section';
    }
}