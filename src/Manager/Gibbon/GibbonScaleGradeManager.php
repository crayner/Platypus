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
 * Time: 11:38
 */
namespace App\Manager\Gibbon;

use App\Entity\ScaleGrade;

/**
 * Class GibbonScaleGradeManager
 * @package App\Manager\Gibbon
 */
class GibbonScaleGradeManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        ScaleGrade::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonScaleGrade';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonScaleGradeID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonScaleID' => [
            'field' => 'scale_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'value' => [
            'field' => 'value',
            'functions' => [
                'length' => 10,
            ],
        ],
        'descriptor' => [
            'field' => 'descriptor',
            'functions' => [
                'length' => 50,
            ],
        ],
        'sequenceNumber' => [
            'field' => 'sequence',
            'functions' => [
                'integer' => '',
            ],
        ],
        'isDefault' => [
            'field' => 'is_default',
            'functions' => [
                'boolean' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonScale';
}
