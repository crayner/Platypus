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
 * Date: 15/08/2018
 * Time: 16:52
 */
namespace App\Form;

use App\Entity\Person;
use App\Entity\SchoolYear;
use App\Form\Subscriber\PreferenceSubscriber;
use App\Manager\ThemeManager;
use App\Manager\TranslationManager;
use Doctrine\ORM\EntityRepository;
use Hillrange\Form\Type\EntityType;
use Hillrange\Form\Type\ImageType;
use Hillrange\Form\Type\TextType;
use Hillrange\Form\Type\ToggleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Language;

/**
 * Class PreferencesType
 * @package App\Form
 */
class PreferencesType extends AbstractType
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
            ->add('receiveNotificationEmails', ToggleType::class,
                [
                    'label' => 'person.receive_notification_emails.label',
                    'help' => 'person.receive_notification_emails.help',
                    'required' => false,
                ]
            )
            ->add('personalCalendarFeed', TextType::class,
                [
                    'label' => 'person.personal_calendar_feed.label',
                    'help' => 'person.personal_calendar_feed.help',
                    'required' => false,
                ]
            )
            ->add('personalBackground', ImageType::class,
                [
                    'label' => 'person.personal_background.label',
                    'help' => 'person.personal_background.help',
                    'deletePhoto' => $options['deleteBackgroundImage'],
                    'fileName' => 'personal_background',
                    'required' => false,
                ]
            )
            ->add('personalTheme', ChoiceType::class,
                [
                    'label' => 'person.personal_theme.label',
                    'help' => 'person.personal_theme.help',
                    'placeholder' => 'person.personal_theme.placeholder',
                    'choices' => ThemeManager::getThemeChoiceList(),
                    'required' => false,
                ]
            )
            ->add('personalLanguage', ChoiceType::class,
                [
                    'label' => 'person.personal_language.label',
                    'help' => 'person.personal_language.help',
                    'placeholder' => 'person.personal_language.placeholder',
                    'required' => false,
                    'constraints' => [
                        new Language(),
                    ],
                    'choices' => array_flip(TranslationManager::$languages),
                ]
            )
        ;
        $builder->addEventSubscriber(new PreferenceSubscriber());
    }

    /**
     * configureOptions
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => 'Person',
                'data_class' => Person::class,
            ]
        );
        $resolver->setRequired(
            [
                'deleteBackgroundImage',
            ]
        );
    }
}