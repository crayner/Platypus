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
 * Date: 19/06/2018
 * Time: 14:04
 */
namespace App\Manager\Settings;

use App\Manager\SettingManager;
use App\Validator\Yaml;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class BehaviourSettings
 * @package App\Manager\Settings
 */
class BehaviourSettings implements SettingCreationInterface
{
    /**
     * getName
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Behaviour';
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

        $setting = $sm->createOneByName('behaviour.enable_descriptors');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Descriptors')
            ->__set('description', 'Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.positive_descriptors');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Positive Descriptors')
            ->__set('description', 'Allowable choices for positive behaviour');
        if (empty($setting->getValue())) {
            $setting->setValue(['Attitude to learning','Collaboration','Community spirit','Creativity','Effort','Leadership','Participation','Persistence','Problem solving','Quality of work','Values'])
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.negative_descriptors');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Negative Descriptors')
            ->__set('description', 'Allowable choices for negative behaviour.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Classwork - Late','Classwork - Incomplete','Classwork - Unacceptable','Disrespectful','Disruptive','Homework - Late','Homework - Incomplete','Homework - Unacceptable','ICT Misuse','Truancy','Other'])
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Descriptors';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $sections['header'] = 'manage_behaviour_settings';

        $settings = [];

        $setting = $sm->createOneByName('behaviour.enable_levels');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(true)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Levels')
            ->__set('description', 'Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
            ;
        }
        $setting->setHideParent('behaviour.enable_levels');
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.levels');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('array')
            ->__set('choice', null)
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDefaultValue(['','Stage 1','Stage 1 (Actioned)','Stage 2','Stage 2 (Actioned)','Stage 3','Stage 3 (Actioned)','Actioned'])
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Levels')
            ->__set('description', 'Allowable choices for severity level (from lowest to highest)');
        if (empty($setting->getValue())) {
            $setting->setValue(['','Stage 1','Stage 1 (Actioned)','Stage 2','Stage 2 (Actioned)','Stage 3','Stage 3 (Actioned)','Actioned'])
            ;
        }
        $setting->setHideParent('behaviour.enable_levels');
        $settings[] = $setting;

        $section['name'] = 'Levels';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('behaviour.enable_behaviour_letters');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('boolean')
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(false)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Enable Behaviour Letters')
            ->__set('description', 'Should automated behaviour letter functionality be enabled?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
            ;
        }
        $setting->setHideParent('behaviour.enable_behaviour_letters');
        $settings[] = $setting;

        $nf = new \NumberFormatter(null, \NumberFormatter::ORDINAL);

        for($i=1; $i<=3; $i++) {
            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter'.$i.'_count');

            $setting
                ->__set('role', 'ROLE_PRINCIPAL')
                ->setSettingType('integer')
                ->__set('choice', null)
                ->setValidators(
                    [
                        new Range(['min' => 1, 'max' => 20])
                    ]
                )
                ->setDefaultValue($i*3)
                ->__set('translateChoice', 'Setting')
                ->__set('displayName', 'Letter '.$i.' Count')
                ->__set('description', 'After how many negative records should letter '.$i.' be sent?');
            if (empty($setting->getValue())) {
                $setting->setValue($i*3);
            }
            $setting->setHideParent('behaviour.enable_behaviour_letters');
            $settings[] = $setting;

            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter'.$i.'_text');

            $setting
                ->__set('role', 'ROLE_PRINCIPAL')
                ->setSettingType('html')
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue("Dear Parent/Guardian,<br/><br/>This letter has been automatically generated to alert you to the fact that your child, [studentName], has reached [behaviourCount] negative behaviour incidents. Please see the list below for the details of these incidents:<br/><br/>[behaviourRecord]<br/><br/>This letter represents the ".$nf->format($i)." communication in a sequence of 3 potential alerts, each of which is more critical than the last.<br/><br/>If you would like more information on this matter, please contact your child's tutor.")
                ->__set('translateChoice', 'Setting')
                ->__set('displayName', 'Letter '.$i.' Text')
                ->__set('description', 'The contents of letter '.$i.', as HTML.');
            if (empty($setting->getValue())) {
                $setting->setValue("Dear Parent/Guardian,<br/><br/>This letter has been automatically generated to alert you to the fact that your child, [studentName], has reached [behaviourCount] negative behaviour incidents. Please see the list below for the details of these incidents:<br/><br/>[behaviourRecord]<br/><br/>This letter represents the ".$nf->format($i)." communication in a sequence of 3 potential alerts, each of which is more critical than the last.<br/><br/>If you would like more information on this matter, please contact your child's tutor.");
            }
            $setting->setHideParent('behaviour.enable_behaviour_letters');
            $settings[] = $setting;
        }

        $section['name'] = 'Behaviour Letters';
        $section['description'] = 'By using an <a href="https://docs.gibbonedu.org/administrators/misc/command-line-tools/" target="_blank">included CLI script</a>, Platypus can be configured to automatically generate and email behaviour letters to parents and tutors, once certain negative behaviour threshold levels have been reached. In your letter text you may use the following fields: [studentName], [rollGroup], [behaviourCount], [behaviourRecord]';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('behaviour.policy_link');

        $setting
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setSettingType('string')
            ->__set('choice', null)
            ->setValidators([
                new Url(),
            ])
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
            ->__set('displayName', 'Policy Link')
            ->__set('description', 'A link to the school behaviour policy.');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

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
