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
 * User' => 'craig
 * Date' => '10/08/2018
 * Time' => '15:11
 */
namespace App\Manager;

use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use App\Util\PersonNameHelper;

/**
 * Class PersonManager
 * @package App\Manager
 */
class PersonManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Person::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'basic.information',
            'label' => 'basic.information.tab',
            'include' => 'Person/basic_information.html.twig',
            'message' => 'basicInformationMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'system.access',
            'label' => 'system.access.tab',
            'include' => 'Person/system_access.html.twig',
            'message' => 'systemAccessMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'contact.information',
            'label' => 'contact.information.tab',
            'include' => 'Person/contact_information.html.twig',
            'message' => 'contactInformationMessage',
            'translation' => 'Person',
        ],
    ];

    /**
     * getFullName
     *
     * @param array $options
     * @return string
     */
    public function getFullName(array $options = []): string
    {
        return PersonNameHelper::getFullName($this->getEntity(), $options);
    }
}