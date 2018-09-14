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
 * Time: 13:00
 */
namespace App\Manager\Gibbon;

use App\Entity\FamilyPerson;

/**
 * Class GibbonFamilyChildManager
 * @package App\Manager\Gibbon
 */
class GibbonFamilyChildManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonFamilyChild';

    /**
     * @var string
     */
    protected $nextGibbonName = '';

    /**
     * @var array
     */
    protected $entityName = [
        FamilyPerson::class,
    ];

    /**
     * @var bool
     */
    public $skipTruncate = [
        FamilyPerson::class => true,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonFamilyChildID' => [
            'field' => '',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonFamilyID' => [
            'field' => 'family_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'gibbonPersonID' => [
            'field' => 'person_id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'comment' => [
            'field' => 'comment',
        ],
    ];

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @return array
     */
    public function postRecord(string $entityName, array $newData): array
    {
        $newData['person_type'] = 'child';
        return $newData;
    }
}
