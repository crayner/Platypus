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
 * Date: 28/06/2018
 * Time: 09:08
 */
namespace App\Manager;

use App\Entity\ExternalAssessment;
use App\Entity\ExternalAssessmentField;
use App\Entity\YearGroup;
use App\Manager\Traits\EntityTrait;
use App\Organism\PrimaryExternalAssessment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ExternalAssessmentManager
 * @package App\Manager
 */
class ExternalAssessmentManager extends TabManager
{
    use EntityTrait;

    /**
     * @var EntityManagerInterface
     */
    private static $em;

    /**
     * @var SettingManager
     */
    private $settingManager;

    /**
     * @var string
     */
    private $entityName = ExternalAssessment::class;

    /**
     * ExternalAssessmentManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager, SettingManager $settingManager)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        self::$em = $entityManager;
        $this->settingManager = $settingManager;
    }

    /**
     * getExternalAssessmentFieldChains
     *
     * @return array
     */
    public static function getExternalAssessmentFieldChains(): array
    {
        $list = self::$em->createQuery('SELECT e.id as eaid, f.id, e.name, c.category FROM ' . ExternalAssessmentField::class . ' f LEFT JOIN f.externalAssessment e JOIN f.externalAssessmentCategory c WHERE e.active = :true GROUP BY e.id, c.category ORDER BY e.name, c.category')
            ->setParameter('true', true)
            ->getArrayResult();

        $results = [];
        foreach($list as $item)
        {
            $result = [];
            $result['data-chained'] = $item['eaid'];
            $result['value'] = $item['id'];
            $result['label'] = $item['category'];
            $results[$item['name']][] = $result;
        }

        return $results;
    }

    /**
     * @return SettingManager
     */
    public function getSettingManager(): SettingManager
    {
        return $this->settingManager;
    }

    /**
     * getPrimaryExternalAssessment
     *
     * @return ArrayCollection
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function getPrimaryExternalAssessment(): ArrayCollection
    {
        $values = $this->getSettingManager()->get('school_admin.primary_external_assessment_by_year_group', []);

        $yearGroups = $this->getEntityManager()->getRepository(YearGroup::class)->findBy([], ['sequence' => 'ASC']);

        $result = new ArrayCollection();

        foreach($yearGroups as $group)
        {
            $xxx = new PrimaryExternalAssessment();
            $id = $group->getId();
            if (isset($values[$id]))
            {
                if (isset($values[$id]['externalAssessment']))
                {
                    $xxx->setExternalAssessment($this->getEntityManager()->getRepository(ExternalAssessment::class)->find($values[$id]['externalAssessment']));
                    if (isset($values[$id]['fieldSet']))
                        $xxx->setFieldSet($values[$id]['fieldSet']);
                }
            }

            $xxx->setYearGroup($group);
            if (! $result->contains($xxx))
                $result->set($xxx->getYearGroup()->getId(), $xxx);
        }

        return $result;
    }

    /**
     * setPrimaryExternalAssessment
     *
     * @param $assessments
     * @return ExternalAssessmentManager
     */
    public function setPrimaryExternalAssessment($assessments): ExternalAssessmentManager
    {
        $new = [];
        foreach($assessments->getIterator() as $yg=>$pea)
        {
            if (is_int($pea->getFieldSet()))
                $pea->setFieldSet($this->getEntityManager()->getRepository(ExternalAssessmentField::class)->find($pea->getFieldSet()));
            if (empty($pea->getFieldSet()) || empty($pea->getExternalAssessment()))
                $pea->setFieldSet(null)->setExternalAssessment(null);
            if ($pea->getYearGroup() instanceof YearGroup && ! isset($new[$pea->getYearGroup()->getId()]))
            {
                $new[$pea->getYearGroup()->getId()]['externalAssessment'] = $pea->getExternalAssessment() ? $pea->getExternalAssessment()->getId() : null;
                $new[$pea->getYearGroup()->getId()]['fieldSet'] = $pea->getFieldSet() ? $pea->getFieldSet()->getId(): null;
            }
        }

        $setting =  $this->settingManager->createOneByName('school_admin.primary_external_assessment_by_year_group');

        $setting->setName('school_admin.primary_external_assessment_by_year_group')
            ->__set('role', 'ROLE_PRINCIPAL')
            ->setType('array')
            ->__set('displayName', 'External Assessment Types by Year Group')
            ->__set('description', 'List of types to make available in Internal Assessments.')
            ->setValue($new)
            ->__set('choice', null)
            ->setValidators(null)
            ->setDefaultValue(null)
            ->__set('translateChoice', 'Setting')
        ;

        $this->settingManager->createSetting($setting->getSetting());

        return $this;
    }

    /**
     * getTabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return Yaml::parse("
details:
    label: external_assessment.details.tab
    include: School/external_assessment_details.html.twig
    message: externalAssessmentDetailsMessage
    translation: School
fields:
    label: external_assessment.fields.tab
    include: School/external_assessment_field_list.html.twig
    message: externalAssessmentFieldsMessage
    translation: School
    display: hasFields
categories:
    label: external_assessment.categories.tab
    include: School/external_assessment_category_manage.html.twig
    message: externalAssessmentFieldsMessage
    translation: School
    display: hasFields
");
    }

    /**
     * canDelete
     *
     * @return bool
     */
    public function canDelete(): bool
    {
        return false;
    }

    /**
     * hasFields
     *
     * @return bool
     */
    public function hasFields(): bool
    {
        if ($this->getEntity() instanceof ExternalAssessment && intval($this->getEntity()->getId()) > 0)
            return true;
        return false;
    }
}