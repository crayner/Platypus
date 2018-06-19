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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Yaml\Yaml;

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
     * @return array
     */
    public function getSettings(SettingManager $sm): array
    {
        $settings = [];

        $setting = $sm->createOneByName('behaviour.enable_descriptors');

        $setting->setName('behaviour.enable_descriptors')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Descriptors')
            ->__set('description', 'Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.positive_descriptors');

        $setting->setName('behaviour.positive_descriptors')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Positive Descriptors')
            ->__set('description', 'Allowable choices for positive behaviour');
        if (empty($setting->getValue())) {
            $setting->setValue(['Attitude to learning','Collaboration','Community spirit','Creativity','Effort','Leadership','Participation','Persistence','Problem solving','Quality of work','Values'])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.negative_descriptors');

        $setting->setName('behaviour.negative_descriptors')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Negative Descriptors')
            ->__set('description', 'Allowable choices for negative behaviour.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Classwork - Late','Classwork - Incomplete','Classwork - Unacceptable','Disrespectful','Disruptive','Homework - Late','Homework - Incomplete','Homework - Unacceptable','ICT Misuse','Truancy','Other'])
                ->__set('choice', null)
                ->setValidators(
                    [
                        new Yaml(),
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

        $sections['header'] = 'manage_behaviour_settings';

        $settings = [];

        $setting = $sm->createOneByName('behaviour.enable_levels');

        $setting->setName('behaviour.enable_levels')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Levels')
            ->__set('description', 'Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(
                    [
                        new NotBlank(),
                        new Yaml(),
                    ]
                )
                ->setDefaultValue(true)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $setting = $sm->createOneByName('behaviour.levels');

        $setting->setName('behaviour.levels')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Levels')
            ->__set('description', 'Allowable choices for severity level (from lowest to highest)');
        if (empty($setting->getValue())) {
            $setting->setValue(['','Stage 1','Stage 1 (Actioned)','Stage 2','Stage 2 (Actioned)','Stage 3','Stage 3 (Actioned)','Actioned'])
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(['','Stage 1','Stage 1 (Actioned)','Stage 2','Stage 2 (Actioned)','Stage 3','Stage 3 (Actioned)','Actioned'])
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Levels';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('behaviour.enable_behaviour_letters');

        $setting->setName('behaviour.enable_behaviour_letters')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Enable Behaviour Letters')
            ->__set('description', 'Should automated behaviour letter functionality be enabled?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(false)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $nf = new \NumberFormatter(null, \NumberFormatter::ORDINAL);

        for($i=1; $i<=3; $i++) {
            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter_'.$i.'_count');

            $setting->setName('behaviour.behaviour_letters_letter_'.$i.'_count')
                ->__set('role', 'ROLE_PRINCIPAL')
                ->setType('integer')
                ->__set('displayName', 'Letter '.$i.' Count')
                ->__set('description', 'After how many negative records should letter '.$i.' be sent?');
            if (empty($setting->getValue())) {
                $setting->setValue($i*3)
                    ->__set('choice', null)
                    ->setValidators([
                        new NotBlank(),
                        new Range(['min' => 1, 'max' => 20])
                    ])
                    ->setDefaultValue($i*3)
                    ->__set('translateChoice', 'Setting');
            }
            $settings[] = $setting;

            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter_'.$i.'_text');

            $setting->setName('behaviour.behaviour_letters_letter_'.$i.'_text')
                ->__set('role', 'ROLE_PRINCIPAL')
                ->setType('html')
                ->__set('displayName', 'Letter '.$i.' Text')
                ->__set('description', 'The contents of letter '.$i.', as HTML.');
            if (empty($setting->getValue())) {
                $setting->setValue("Dear Parent/Guardian,<br/><br/>This letter has been automatically generated to alert you to the fact that your child, [studentName], has reached [behaviourCount] negative behaviour incidents. Please see the list below for the details of these incidents:<br/><br/>[behaviourRecord]<br/><br/>This letter represents the ".$nf->format($i)." communication in a sequence of 3 potential alerts, each of which is more critical than the last.<br/><br/>If you would like more information on this matter, please contact your child's tutor.")
                    ->__set('choice', null)
                    ->setValidators(null)
                    ->setDefaultValue("Dear Parent/Guardian,<br/><br/>This letter has been automatically generated to alert you to the fact that your child, [studentName], has reached [behaviourCount] negative behaviour incidents. Please see the list below for the details of these incidents:<br/><br/>[behaviourRecord]<br/><br/>This letter represents the ".$nf->format($i)." communication in a sequence of 3 potential alerts, each of which is more critical than the last.<br/><br/>If you would like more information on this matter, please contact your child's tutor.")
                    ->__set('translateChoice', 'Setting');
            }
            $settings[] = $setting;
        }

        $section['name'] = 'Behaviour Letters';
        $section['description'] = 'By using an <a href="https://docs.gibbonedu.org/administrators/misc/command-line-tools/" target="_blank">included CLI script</a>, Platypus can be configured to automatically generate and email behaviour letters to parents and tutors, once certain negative behaviour threshold levels have been reached. In your letter text you may use the following fields: [studentName], [rollGroup], [behaviourCount], [behaviourRecord]';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $setting = $sm->createOneByName('behaviour.policy_link');

        $setting->setName('behaviour.policy_link')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('string')
            ->__set('displayName', 'Policy Link')
            ->__set('description', 'A link to the school behaviour policy.');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators([
                    new Url(),
                ])
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting');
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        return $sections;
    }
}