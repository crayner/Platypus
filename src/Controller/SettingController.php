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
use App\Manager\CollectionManager;
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
    public function editName($name, $closeWindow = null,  Request $request, SettingRepository $settingRepository)
    {
        $setting = null;
        $original = $name;
        do
        {
            $setting = $settingRepository->findOneByName($name);

            if (is_null($setting))
            {
                $name = explode('.', $name);
                array_pop($name);
                $name = implode('.', $name);
            }

        } while (is_null($setting) && false !== mb_strpos($name, '.'));

        if (is_null($setting))
            throw new \InvalidArgumentException('The System setting of name: ' . $original . ' was not found');

        return $this->forward(SettingController::class.'::edit', ['id' => $setting->getId(), 'closeWindow' => $closeWindow]);
    }

    /**
     * resource Settings
     *
     * @param Request $request
     * @Route("/setting/resource/manage/", name="resource_settings_manage")
     * @IsGranted("ROLE_PRINCIPAL")
     */
    public function resourceSettings(Request $request, EntityManagerInterface $entityManager)
    {
        $settings = [];

        $setting = $entityManager->getRepository(Setting::class)->findOneByName('resources.categories');
        if (is_null($setting))
            $setting = new Setting();
        $setting->setName('resources.categories');
        $setting->setRole('ROLE_PRINCIPAL');
        $setting->setType('array');
        $setting->setDisplayName('Categories');
        $setting->setDescription('Allowable choices for category.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Article', 'Book', 'Document', 'Graphic', 'Idea', 'Music', 'Object', 'Painting', 'Person', 'Photo', 'Place', 'Poetry', 'Prose', 'Rubric', 'Text', 'Video', 'Website', 'Work Sample', 'Other']);
            $setting->setchoice(null);
            $setting->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            );
            $setting->setDefaultValue(null);
            $setting->setTranslateChoice('Setting');
        }
        $settings[] = $setting;

        $setting = $entityManager->getRepository(Setting::class)->findOneByName('resources.purposes_general');
        if (is_null($setting))
            $setting = new Setting();
        $setting->setName('resources.purposes_general');
        $setting->setRole('ROLE_PRINCIPAL');
        $setting->setType('array');
        $setting->setDisplayName('Purposes (General)');
        $setting->setDescription('Allowable choices for purpose when creating a resource.');
        if (empty($setting->getValue())) {
            $setting->setValue(['Assessment Aid', 'Concept', 'Inspiration', 'Learner Profile', 'Mass Mailer Attachment', 'Provocation', 'Skill', 'Teaching and Learning Strategy', 'Other']);
            $setting->setchoice(null);
            $setting->setValidators(
                [
                    new NotBlank(),
                    new Yaml(),
                ]
            );
            $setting->setDefaultValue(null);
            $setting->setTranslateChoice('Setting');
        }
        $settings[] = $setting;

        $setting = $entityManager->getRepository(Setting::class)->findOneByName('resources.purposes_restricted');
        if (is_null($setting))
            $setting = new Setting();
        $setting->setName('resources.purposes_restricted');
        $setting->setRole('ROLE_PRINCIPAL');
        $setting->setType('array');
        $setting->setDisplayName('Purposes (Restricted) ');
        $setting->setDescription('Additional allowable choices for purpose when creating a resource, for those with "Manage All Resources" rights.');
        if (empty($setting->getValue())) {
            $setting->setValue([]);
            $setting->setchoice(null);
            $setting->setValidators(
                [
                    new Yaml(),
                ]
            );
            $setting->setDefaultValue(null);
            $setting->setTranslateChoice('Setting');
        }
        $settings[] = $setting;

        $settings['name'] = 'Resource Settings';
        $settings['description'] = '';

        $request->getSession()->set('manage_settings', $settings);

        return $this->forward(SettingController::class.'::manageMultipleSettings');
    }

    /**
     * manage Multiple Settings
     *
     * @param Request $request
     * @return Response
     * @Route("/setting/multiple/manage/", name="multiple_settings_manage")
     */
    public function manageMultipleSettings(Request $request, CollectionManager $collectionManager, SettingManager $settingManager)
    {
        $settings = $request->getSession()->get('manage_settings');
        $name = $settings['name'];
        unset($settings['name']);
        $description = null;
        if (isset($settings['description'])){
            $description = $settings['description'];
            unset($settings['description']);
        }

        $collectionManager->setCollection(new ArrayCollection($settings));

        $form = $this->createForm(CollectionType::class, $collectionManager,
            [
                'allow_up' => false,
                'allow_down' => false,
                'entry_type' => MultipleSettingType::class,
                'entry_options_data_class' => Setting::class,
                'entry_options' => [
                    'all_data' => $collectionManager->getCollection(),
                ],
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            foreach($form->get('collection')->getData()->toArray() as $setting)
                $settingManager->getEntityManager()->persist($setting);
            $settingManager->getEntityManager()->flush();
        }


        return $this->render('Setting/multiple.html.twig',
            [
                'form' => $form->createView(),
                'name' => $name,
                'description' => $description,
                'fullForm' => $form,
            ]
        );
    }
}