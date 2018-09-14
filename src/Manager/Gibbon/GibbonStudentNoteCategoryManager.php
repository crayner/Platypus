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
 * Time: 12:10
 */

namespace App\Manager\Gibbon;


use App\Entity\StudentNoteCategory;

class GibbonStudentNoteCategoryManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        StudentNoteCategory::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonStudentNoteCategory';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonStudentNoteCategoryID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 30,
            ],
        ],
        'template' => [
            'field' => 'template',
            'functions' => [
                'nullable' => '',
            ],
        ],
        'active' => [
            'field' => 'active',
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
