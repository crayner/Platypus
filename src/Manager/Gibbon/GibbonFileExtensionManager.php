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
 * Time: 10:40
 */
namespace App\Manager\Gibbon;

use App\Entity\FileExtension;

/**
 * Class GibbonFileExtensionManager
 * @package App\Manager\Gibbon
 */
class GibbonFileExtensionManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        FileExtension::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonFileExtension';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonFileExtensionID' => [
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
        'extension' => [
            'field' => 'extension',
            'functions' => [
                'length' => 7,
            ],
        ],
        'type' => [
            'field' => 'file_type',
            'functions' => [
                'length' => 32,
                'enum' => '',
            ],
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}
