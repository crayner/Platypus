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
     * @param SettingManager $sm
     * @return SettingCreationInterface
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function getSettings(SettingManager $sm): SettingCreationInterface
    {
        $settings = [];

        $setting = $sm->createOneByName('library.default_loan_length');

        $setting
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setSettingType('integer')
            ->setDisplayName('Default Loan Length')
           ->setDescription('The standard loan length for a library item, in days');
        if (empty($setting->getValue())) {
            $setting->setValue('7')

                ->setValidators(
                    [
                        new Range(['min' => 0, 'max' => 31]),
                    ]
                )
                ->setDefaultValue('7')
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('library.browse_bgcolour');

        $setting
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setSettingType('colour')
            ->setDisplayName('Browse Library BG Colour ')
           ->setDescription('Background colour used behind library browsing screen.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)

                ->setValidators(
                    [
                        new NotBlank(),
                        new Colour(),
                    ]
                )
                    ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('library.browse_bgimage');

        $setting
            ->__set('role', 'ROLE_HEAD_TEACHER')
            ->setSettingType('image')
            ->setDisplayName('Browse Library BG Image')
           ->setDescription('URL to background image used behind library browsing screen.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)

                ->setValidators(
                    [
                        new BackgroundImage(),
                    ]
                )
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

        $this->sections = $sections;
        return $this;
    }

    /**
     * @var array
     */
    private $sections;

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }
}
