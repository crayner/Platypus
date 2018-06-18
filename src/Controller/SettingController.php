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
 * Date: 10/06/2018
 * Time: 12:21
 */
namespace App\Controller;

use App\Form\SectionSettingType;
use App\Manager\MultipleSettingManager;
use App\Manager\SettingManager;
use App\Repository\SettingRepository;
use App\Validator\BackgroundImage;
use App\Validator\Yaml;
use Hillrange\Form\Validator\Colour;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class SettingController extends Controller
{
    /**
     * @param         $name
     * @param Request $request
     *
     * @return Response
     * @Route("/setting/{name}/edit/{closeWindow}", name="setting_edit_name")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function editName($name, $closeWindow = null, Request $request, SettingRepository $settingRepository)
    {
        $setting = null;
        $original = $name;
        do {
            $setting = $settingRepository->findOneByName($name);

            if (is_null($setting)) {
                $name = explode('.', $name);
                array_pop($name);
                $name = implode('.', $name);
            }

        } while (is_null($setting) && false !== mb_strpos($name, '.'));

        if (is_null($setting))
            throw new \InvalidArgumentException('The System setting of name: ' . $original . ' was not found');

        return $this->forward(SettingController::class . '::edit', ['id' => $setting->getId(), 'closeWindow' => $closeWindow]);
    }

    /**
     * resource Settings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @return Response
     * @Route("/setting/resource/manage/", name="resource_settings_manage")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function resourceSettings(Request $request, SettingManager $sm)
    {
        $settings = [];

        $setting = $sm->createOneByName('resources.categories');

        $setting->setName('resources.categories')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Categories')
            ->__set('description', 'Allowable choices for category.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Article', 'Book', 'Document', 'Graphic', 'Idea', 'Music', 'Object', 'Painting', 'Person', 'Photo', 'Place', 'Poetry', 'Prose', 'Rubric', 'Text', 'Video', 'Website', 'Work Sample', 'Other'])
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

        $setting = $sm->createOneByName('resources.purposes_general');

        $setting->setName('resources.purposes_general')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Purposes (General)')
            ->__set('description', 'Allowable choices for purpose when creating a resource.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Assessment Aid', 'Concept', 'Inspiration', 'Learner Profile', 'Mass Mailer Attachment', 'Provocation', 'Skill', 'Teaching and Learning Strategy', 'Other'])
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

        $setting = $sm->createOneByName('resources.purposes_restricted');

        $setting->setName('resources.purposes_restricted')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'Purposes (Restricted) ')
            ->__set('description', 'Additional allowable choices for purpose when creating a resource, for those with "Manage All Resources" rights.');
        if (empty($setting->getValue())) {
            $setting->setValue([])
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

        $section['name'] = 'Resource Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $request->getSession()->set('manage_settings', $sections);

        return $this->forward(SettingController::class . '::manageMultipleSettings');
    }

    /**
     * manage Multiple Settings
     *
     * @param Request $request
     * @return Response
     * @Route("/setting/multiple/manage/", name="multiple_settings_manage")
     */
    public function manageMultipleSettings(Request $request, MultipleSettingManager $multipleSettingManager, SettingManager $settingManager)
    {
        foreach ($request->getSession()->get('manage_settings') as $section)
            $multipleSettingManager->addSection($section);
        $multipleSettingManager->setHeader('Manage Resource Settings');

        $form = $this->createForm(SectionSettingType::class, $multipleSettingManager);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($multipleSettingManager->getSections() as $section)
                foreach ($section['settings'] as $setting)
                    $settingManager->getEntityManager()->persist($setting->getSetting());
            $settingManager->getEntityManager()->flush();
        }


        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'fullForm' => $form,
            ]
        );
    }

    /**
     * plannerSettings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @return Response
     * @Route("/setting/planner/manage/", name="manage_planner_settings")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function plannerSettings(Request $request, SettingManager $sm)
    {
        $settings = [];

        $setting = $sm->createOneByName('planner.lesson_details_template');

        $setting->setName('planner.lesson_details_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Lesson Details Template')
            ->__set('description', 'Template to be inserted into Lesson Details field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.teachers_notes_template');

        $setting->setName('planner.teachers_notes_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Teacher\'s Notes Template')
            ->__set('description', 'Template to be inserted into Teacher\'s Notes field');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.unit_outline_template');

        $setting->setName('planner.unit_outline_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Unit Outline Template')
            ->__set('description', 'Template to be inserted into Unit Outline section of planner');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.smart_block_template');

        $setting->setName('planner.smart_block_template')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('html')
            ->__set('displayName', 'Smart Block Template')
            ->__set('description', 'Template to be inserted into new block in Smart Unit');
        if (empty($setting->getValue())) {
            $setting->setValue(null)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Planner Templates';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];


        $setting = $sm->createOneByName('planner.make_units_public');

        $setting->setName('planner.make_units_public')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Make Units Public')
            ->__set('description', 'Enables a public listing of units, with teachers able to opt in to share units.');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.share_unit_outline');

        $setting->setName('planner.share_unit_outline')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Share Unit Outline')
            ->__set('description', 'Allow users who do not have access to the unit planner to see Unit Outlines via the lesson planner?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.allow_outcome_editing');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Allow Outcome Editing')
            ->__set('description', 'Should the text within outcomes be editable when planning lessons and units?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_parents');

        $setting->setName('planner.allow_outcome_editing')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Sharing Default: Parents')
            ->__set('description', 'When adding lessons and deploying units, should sharing default for parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.sharing_default_students');

        $setting->setName('planner.sharing_default_students')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Sharing Default: Students')
            ->__set('description', 'When adding lessons and deploying units, should sharing default for students?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Access Settings';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $settings = [];


        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_behaviour');

        $setting->setName('planner.parent_weekly_email_summary_include_behaviour')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Parent Weekly Email Summary Include Behaviour')
            ->__set('description', 'Should behaviour information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(true)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;


        $setting = $sm->createOneByName('planner.parent_weekly_email_summary_include_markbook');

        $setting->setName('planner.parent_weekly_email_summary_include_markbook')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('boolean')
            ->__set('displayName', 'Parent Weekly Email Summary Include Markbook')
            ->__set('description', 'Should Markbook information be included in the weekly planner email summary that goes out to parents?');
        if (empty($setting->getValue())) {
            $setting->setValue(false)
                ->__set('choice', null)
                ->setValidators(null)
                ->setDefaultValue(null)
                ->__set('translateChoice', 'Setting')
            ;
        }
        $settings[] = $setting;

        $section['name'] = 'Miscellaneous';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;
        $request->getSession()->set('manage_settings', $sections);

        return $this->forward(SettingController::class . '::manageMultipleSettings');
    }
    /**
     * library Settings
     *
     * @param Request $request
     * @param SettingManager $sm
     * @return Response
     * @Route("/setting/library/manage/", name="manage_library_settings")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function librarySettings(Request $request, SettingManager $sm)
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

        $request->getSession()->set('manage_settings', $sections);

        return $this->forward(SettingController::class . '::manageMultipleSettings');
    }

    /**
     * Delete Setting Image
     * @Route("/setting/{route}/image/{name}/delete/", name="delete_setting_image")
     * @param $name
     * @param $route
     * @param SettingManager $settingManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function delete_setting_image($name, $route, SettingManager $settingManager)
    {
        $settingManager->set($name, null);

        return $this->redirectToRoute($route);
    }
}
