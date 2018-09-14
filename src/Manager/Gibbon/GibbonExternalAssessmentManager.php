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
 * Date: 14/09/2018
 * Time: 10:17
 */
namespace App\Manager\Gibbon;

use App\Entity\ExternalAssessment;

/**
 * Class GibbonExternalAssessmentManager
 * @package App\Manager\Gibbon
 */
class GibbonExternalAssessmentManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        ExternalAssessment::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonExternalAssessment';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonExternalAssessmentID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 50,
            ],
        ],
        'nameShort' => [
            'field' => 'name_short',
            'functions' => [
                'length' => 10,
            ],
        ],
        'description' => [
            'field' => 'description',
        ],
        'website' => [
            'field' => 'website',
            'functions' => [
                'length' => 255,
            ],
        ],
        'active' => [
            'field' => 'active',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'allowFileUpload' => [
            'field' => 'allow_file_upload',
            'functions' => [
                'boolean' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}
