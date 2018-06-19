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
 * Time: 11:34
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Validator\BackgroundImage;
use Hillrange\Form\Validator\Colour;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Class LibrarySettings
 * @package App\Manager\Settings
 */
class LibrarySettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Library';
    }

    /**
     * getSettings
     *
     * @return array
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('library.default_loan_length');

        $setting->setName('library.default_loan_length')
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setType('integer')
            ->__set('displayName', 'Default Loan Length')
            ->__set('description', 'The standard loan length for a library item, in days');
        if (empty($setting->getValue())) {
            $setting->setValue('7')
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Range(['min' => 0, 'max' => 31]),
                    ]
                )
                ->setDefaultValue('7')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('library.browse.bg.colour');

        $setting->setName('library.browse.bg.colour')
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setType('colour')
            ->__set('displayName', 'Browse Library BG Colour ')
            ->__set('description', 'Background colour used behind library browsing screen.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Colour(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('library.browse.bg.image');

        $setting->setName('library.browse.bg.image')
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setType('image')
            ->__set('displayName', 'Browse Library BG Image')
            ->__set('description', 'URL to background image used behind library browsing screen.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new BackgroundImage(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Descriptors';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $sections['header'] = 'manage_library_settings';

        return $sections;
    }
}