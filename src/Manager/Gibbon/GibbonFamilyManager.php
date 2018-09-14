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
 * Time: 12:21
 */
namespace App\Manager\Gibbon;

use App\Entity\Family;

/**
 * Class GibbonFamilyManager
 * @package App\Manager\Gibbon
 */
class GibbonFamilyManager extends GibbonTransferManager
{
    /**
     * @var string
     */
    protected $gibbonName = 'gibbonFamily';

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonFamilyAdult';

    /**
     * @var array
     */
    protected $entityName = [
        Family::class,
    ];

    /**
     * @var array
     */
    protected $transferRules = [
        'gibbonFamilyID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'homeAddress' => [
            'field' => '',
            'combineField' => [
                'homeAddress',
                'homeAddressDistrict',
                'homeAddressCountry',
            ],
            'joinTable' => [
                'name' => 'family_address',
                'join' => 'family_id',
                'inverse' => 'address_id',
                'call' => ['function' => 'getAddressDetails', 'options' => ''],
            ],
        ],
        'homeAddressDistrict' => [
            'field' => '',
        ],
        'homeAddressCountry' => [
            'field' => '',
        ],
        'name' => [
            'field' => 'name',
            'functions' => [
                'length' => 100,
            ],
        ],
        'nameAddress' => [
            'field' => 'name_address',
            'functions' => [
                'length' => 100,
            ],
        ],
        'status' => [
            'field' => 'status',
            'functions' => [
                'enum' => '',
            ],
        ],
        'languageHomePrimary' => [
            'field' => 'language_home_primary',
            'functions' => [
                'length' => 30,
            ],
        ],
        'languageHomeSecondary' => [
            'field' => 'language_home_secondary',
            'functions' => [
                'length' => 30,
                'nullable' => '',
            ],
        ],
        'familySync' => [
            'field' => 'family_sync',
            'functions' => [
                'length' => 50,
                'nullable' => '',
            ],
        ],
    ];

    /**
     * getAddressDetails
     *
     * @param $value
     * @return array
     */
    public function getAddressDetails($value)
    {
        if (empty($value[0]))
            return [];

        trigger_error('The address needs handling...');
    }
}
