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
class BehaviourSettings extends SettingCreationManager
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
        $this->setSettingManager($sm);

        $setting = $sm->createOneByName('behaviour.enable_descriptors')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Descriptors')
            ->setDescription('Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('behaviour.positive_descriptors')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Positive Descriptors')
            ->setDescription('Allowable choices for positive behaviour');
        if (empty($setting->getValue()))
            $setting->setValue(['Attitude to learning','Collaboration','Community spirit','Creativity','Effort','Leadership','Participation','Persistence','Problem solving','Quality of work','Values']);
        $this->addSetting($setting, []);

        $setting = $sm->createOneByName('behaviour.negative_descriptors')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Negative Descriptors')
            ->setDescription('Allowable choices for negative behaviour.');
        if (empty($setting->getValue()))
            $setting->setValue(['Classwork - Late','Classwork - Incomplete','Classwork - Unacceptable','Disrespectful','Disruptive','Homework - Late','Homework - Incomplete','Homework - Unacceptable','ICT Misuse','Truancy','Other']);
        $this->addSetting($setting, []);

        $this->addSection('Descriptors');

        $this->setSectionsHeader('manage_behaviour_settings');

        $setting = $sm->createOneByName('behaviour.enable_levels')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Levels')
            ->setDescription('Setting to No reduces complexity of behaviour tracking.');
        if (empty($setting->getValue()))
            $setting->setValue(true);
        $this->addSetting($setting, ['hideParent' => 'behaviour.enable_levels']);

        $setting = $sm->createOneByName('behaviour.levels')
            ->setSettingType('array')
            ->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            )
            ->setDisplayName('Levels')
            ->setDescription('Allowable choices for severity level (from lowest to highest)');
        if (empty($setting->getValue()))
            $setting->setValue(['','Stage 1','Stage 1 (Actioned)','Stage 2','Stage 2 (Actioned)','Stage 3','Stage 3 (Actioned)','Actioned']);
        $this->addSetting($setting, ['hideParent' => 'behaviour.enable_levels']);


        $this->addSection('Levels');

        $setting = $sm->createOneByName('behaviour.enable_behaviour_letters')
            ->setSettingType('boolean')
            ->setDisplayName('Enable Behaviour Letters')
            ->setDescription('Should automated behaviour letter functionality be enabled?');
        if (empty($setting->getValue()))
            $setting->setValue(false);
        $this->addSetting($setting, ['hideParent' => 'behaviour.enable_behaviour_letters']);

        $nf = new \NumberFormatter(null, \NumberFormatter::ORDINAL);

        for($i=1; $i<=3; $i++) {
            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter'.$i.'_count')
                ->setSettingType('integer')
                ->setValidators(
                    [
                        new Range(['min' => 1, 'max' => 20])
                    ]
                )
                ->setDisplayName('Letter '.$i.' Count')
                ->setDescription('After how many negative records should letter '.$i.' be sent?');
            if (empty($setting->getValue()))
                $setting->setValue($i*3);
            $this->addSetting($setting, ['hideParent' => 'behaviour.enable_behaviour_letters']);

            $setting = $sm->createOneByName('behaviour.behaviour_letters_letter'.$i.'_text')
                ->setSettingType('html')
                ->setDisplayName('Letter '.$i.' Text')
                ->setDescription('The contents of letter '.$i.', as HTML.');
            if (empty($setting->getValue()))
                $setting->setValue("Dear Parent/Guardian,<br/><br/>This letter has been automatically generated to alert you to the fact that your child, [studentName], has reached [behaviourCount] negative behaviour incidents. Please see the list below for the details of these incidents:<br/><br/>[behaviourRecord]<br/><br/>This letter represents the ".$nf->format($i)." communication in a sequence of 3 potential alerts, each of which is more critical than the last.<br/><br/>If you would like more information on this matter, please contact your child's tutor.");
            $this->addSetting($setting, ['hideParent' => 'behaviour.enable_behaviour_letters']);
        }

        $this->addSection('Behaviour Letters', 'By using an <a href="https://docs.gibbonedu.org/administrators/misc/command-line-tools/" target="_blank">included CLI script</a>, Platypus can be configured to automatically generate and email behaviour letters to parents and tutors, once certain negative behaviour threshold levels have been reached. In your letter text you may use the following fields: [studentName], [rollGroup], [behaviourCount], [behaviourRecord]');

        $setting = $sm->createOneByName('behaviour.policy_link')
            ->setSettingType('string')
            ->setValidators([
                new Url(),
            ])
            ->setDisplayName('Policy Link')
            ->setDescription('A link to the school behaviour policy.');
        if (empty($setting->getValue()))
            $setting->setValue(null);
        $this->addSetting($setting, []);

        $this->addSection('Miscellaneous');

        $this->setSettingManager(null);

        return $this;
    }
}
