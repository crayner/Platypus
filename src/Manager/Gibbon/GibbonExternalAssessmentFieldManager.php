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
 * Date: 4/10/2018
 * Time: 16:01
 */
namespace App\Manager\Gibbon;

use App\Entity\ExternalAssessmentCategory;
use App\Entity\ExternalAssessmentField;

/**
 * Class GibbonExternalAssessmentFieldManager
 * @package App\Manager\Gibbon
 */
class GibbonExternalAssessmentFieldManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        ExternalAssessmentField::class,
        ExternalAssessmentCategory::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonExternalAssessmentField';

    /**
     * @var array
     */
    protected $requireBefore = [
        'gibbonExternalAssessment',
        'gibbonYearGroup',
    ];

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonExternalAssessmentID' => [
            'field' => 'external_assessment_id',
            'functions' => [
                'integer' => null,
            ],
            'link' => [
                [
                    'field' => 'external_assessment_id',
                    'functions' => [
                        'integer' => null,
                    ],
                    'entityName' => ExternalAssessmentCategory::class,
                ],
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 50,
            ],
        ],
        'gibbonExternalAssessmentFieldID' => [
            'field' => 'id',
            'functions' => [
                'integer' => null,
            ],
        ],
        'order' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => null,
            ],
            'link' => [
                [
                    'field' => 'sequence',
                    'functions' => [
                        'integer' => null,
                    ],
                    'entityName' => ExternalAssessmentCategory::class,
                ],
            ],
        ],
        'category' => [
            'field' => 'category_id',
            'functions' => [
                'call' => ['function' => 'categoryID'],
            ],
            'link' => [
                [
                    'field' => 'category',
                    'functions' => [
                        'call' => ['function' => 'categoryName'],
                        'length' => 50,
                    ],
                    'entityName' => ExternalAssessmentCategory::class,
                ],
            ],
        ],
        'gibbonScaleID' => [
            'field' => null,
            'link' => [
                [
                    'field' => 'scale_id',
                    'functions' => [
                        'integer' => null,
                    ],
                    'entityName' => ExternalAssessmentCategory::class,
                ],
            ],
        ],
        'gibbonYearGroupIDList' => [
            'field' => null,
            'joinTable' => [
                'name' => 'external_assessment_field_year_group',
                'join' => 'external_assessment_id',
                'inverse' => 'year_group_id',
                'call' => ['function' =>'getYearGroups', 'options' => ''],
            ],
        ],
    ];

    /**
     *
     */
    private $categories = [];

    /**
     * categoryID
     *
     * @param $value
     */
    public function categoryID($value)
    {
        $value = explode('_', $value);

        if (in_array($value[1], $this->categories))
            $id = array_search($value[1], $this->categories);
        else {
            $id = count($this->categories) + 1;
            $this->categories[$id] = $value[1];
        }

        return $id;
    }

    /**
     * getYearGroups
     *
     * @param $value
     * @return array
     */
    public function getYearGroups($value): array
    {
        if (empty($value))
            return [];
        $value = explode(',',$value);
        foreach($value as $q=>$w)
            $value[$q] = intval($w);
        return $value;
    }

    /**
     * categoryName
     *
     * @param $value
     * @return mixed
     */
    public function categoryName($value)
    {
        $value = explode('_', $value);

        if (in_array($value[1], $this->categories))
            $id = array_search($value[1], $this->categories);
        else {
            $id = count($this->categories) + 1;
            $this->categories[$id] = $value[1];
        }

        return $value[1];
    }

}
