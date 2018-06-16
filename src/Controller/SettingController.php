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

use App\Entity\Setting;
use App\Form\CollectionType;
use App\Form\MultipleSettingType;
use App\Form\SectionSettingType;
use App\Manager\CollectionManager;
use App\Manager\MultipleSettingManager;
use App\Manager\SettingManager;
use App\Repository\SettingRepository;
use App\Validator\Yaml;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;

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

        $setting = $sm->getEntityManager()->getRepository(Setting::class)->findOneByName('planner.lesson_details_template');
        if (is_null($setting))
            $setting = new Setting();
        $setting->setName('planner.lesson_details_template');
        $setting->setRole('ROLE_PRINCIPAL');
        $setting->setType('html');
        $setting->setDisplayName('Lesson Details Template');
        $setting->setDescription('Template to be inserted into Lesson Details field');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
            $setting->setchoice(null);
            $setting->setValidators(null);
            $setting->setDefaultValue(null);
            $setting->setTranslateChoice('Setting');
        }
        $settings[] = $setting;

        $setting = $sm->getEntityManager()->getRepository(Setting::class)->findOneByName('planner.teachers_notes_template');
        if (is_null($setting))
            $setting = new Setting();
        $setting->setName('planner.teachers_notes_template');
        $setting->setRole('ROLE_PRINCIPAL');
        $setting->setType('html');
        $setting->setDisplayName('Teacher\'s Notes Template');
        $setting->setDescription('Template to be inserted into Teacher\'s Notes field');
        if (empty($setting->getValue())) {
            $setting->setValue(null);
            $setting->setchoice(null);
            $setting->setValidators(null);
            $setting->setDefaultValue(null);
            $setting->setTranslateChoice('Setting');
        }
        $settings[] = $setting;

        $sections = [];

        $section['name'] = 'Planner Templates';
        $section['description'] = '';
        $section['settings'] = $settings;

        $sections[] = $section;

        $settings = [];

        $request->getSession()->set('manage_settings', $sections);

        return $this->forward(SettingController::class . '::manageMultipleSettings');
    }
}
