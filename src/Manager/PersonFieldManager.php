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
 * Date: 9/09/2018
 * Time: 16:04
 */
namespace App\Manager;

use App\Entity\PersonField;
use App\Manager\Traits\EntityTrait;
use Hillrange\Form\Util\TemplateManagerInterface;

/**
 * Class PersonFieldManager
 * @package App\Manager
 */
class PersonFieldManager implements TemplateManagerInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = PersonField::class;

    /**
     * getTranslationsDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return 'Person';
    }

    /**
     * isLocale
     *
     * @return bool
     */
    public function isLocale(): bool
    {
        return true;
    }

    /**
     * getTargetDivision
     *
     * @return string
     */
    public function getTargetDivision(): string
    {
        return 'pageContent';
    }

    /**
     * getTemplate
     *
     * @return array
     */
    public function getTemplate(): array
    {
        return [
            'form' => [
                'url' => '/person/custom/field/{id}/manage/',
                'url_options' => [
                    '{id}' => 'id'
                ],
            ],
            'container' => $this->getContainer(),
        ];
    }

    /**
     * getContainer
     *
     * @return array
     */
    private function getContainer(): array
    {
        $container = [
            'panel' => $this->getPanel(),
        ];

        return $container;
    }

    /**
     * getPanel
     *
     * @return array
     */
    private function getPanel(): array
    {
        $panel = [
            'colour' => 'dark',
            'label' => 'person.field.title',
            'rows' => $this->getRows(),
            'buttons' => [
                [
                    'type' => 'save',
                ],
                [
                    'type' => 'add',
                    'url' => '/person/custom/field/Add/manage/',
                    'url_type' => 'redirect',
                    'display' => 'isValidEntity'
                ],
            ],
        ];

        return $panel;
    }

    /**
     * getRows
     *
     * @return array
     */
    private function getRows(): array
    {
        $rows = [
            [
                'class' => 'row',
                'columns' => [
                    [
                        'class' => 'col-7 offset-5 card alert-success',
                        'form' => ['fieldList' => 'row'],
                    ],
                ],
            ],
            [
                'class' => 'row',
                'columns' => [
                    [
                        'class' => 'col-8 card',
                        'rows' => [
                            [
                                'class' => 'row',
                                'columns' => [
                                    [
                                        'class' => 'col-8 card',
                                        'form' => ['name' => 'row'],
                                    ],
                                    [
                                        'class' => 'col-4 card',
                                        'form' => ['active' => 'row'],
                                    ],
                                ],
                            ],
                            [
                                'class' => 'row',
                                'columns' => [
                                    [
                                        'class' => 'col-8 card',
                                        'form' => ['type' => 'row'],
                                    ],
                                    [
                                        'class' => 'col-4 card',
                                        'form' => ['required' => 'row'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'class' => 'col-4 card',
                        'form' => ['description' => 'row'],
                    ],
                ],
            ],
            $this->getRowTwo(),
            $this->getRowThree(),
            [
                'class' => 'row',
                'columns' => [
                    [
                        'class' => 'offset-8 col-4 card',
                        'form' => ['forPublicRegistration' => 'row'],
                    ],
                ],
            ],
        ];

        return $rows;
    }

    /**
     * getRowTwo
     *
     * @return array
     */
    private function getRowTwo(): array
    {
        return [
            'class' => 'row',
            'columns' => [
                [
                    'class' => 'col-4 card',
                    'form' => ['forStudent' => 'row'],
                ],
                [
                    'class' => 'col-4 card',
                    'form' => ['forStaff' => 'row'],
                ],
                [
                    'class' => 'col-4 card',
                    'form' => ['forParent' => 'row'],
                ],
            ],
        ];
    }

    /**
     * getRowThree
     *
     * @return array
     */
    private function getRowThree(): array
    {
        return [
            'class' => 'row',
            'columns' => [
                [
                    'class' => 'col-4 card',
                    'form' => ['forOther' => 'row'],
                ],
                [
                    'class' => 'col-4 card',
                    'form' => ['forDataUpdater' => 'row'],
                ],
                [
                    'class' => 'col-4 card',
                    'form' => ['forApplicationForm' => 'row'],
                ],
            ],
        ];
    }

    /**
     * isValidEntity
     *
     * @return bool
     */
    public function isValidEntity(): bool
    {
        dump($this->getEntity()->getId());
        if (is_int($this->getEntity()->getId()) && $this->getEntity()->getId() > 0)
            return true;
        return false;
    }
}