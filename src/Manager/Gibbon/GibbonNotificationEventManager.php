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
 * Time: 11:11
 */

namespace App\Manager\Gibbon;


use App\Entity\NotificationEvent;

class GibbonNotificationEventManager extends GibbonTransferManager
{
    /**
     * @var array
     */
    protected $entityName = [
        NotificationEvent::class,
    ];

    /**
     * @var string
     */
    protected $gibbonName = 'gibbonNotificationEvent';

    /**
     * @var array
     */

    protected $transferRules = [
        'gibbonNotificationEventID' => [
            'field' => 'id',
            'functions' => [
                'integer' => '',
            ],
        ],
        'event' => [
            'field' => 'event',
            'functions' => [
                'length' => 90,
            ],
        ],
        'type' => [
            'field' => 'event_type',
            'functions' => [
                'length' => 10,
                'enum' => '',
            ],
        ],
        'scopes' => [
            'field' => 'scopes',
            'functions' => [
                'json_array' => '',
            ],
        ],
        'active' => [
            'field' => 'active',
            'functions' => [
                'boolean' => '',
            ],
        ],
        'moduleName' => [
            'field' => '',
        ],
        'actionName' => [
            'field' => '',
        ],
    ];

    /**
     * @var string
     */
    protected $nextGibbonName = 'gibbonRole';
}
