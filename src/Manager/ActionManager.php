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
 * Date: 10/09/2018
 * Time: 11:38
 */
namespace App\Manager;

use App\Entity\Action;
use App\Entity\PersonRole;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Hillrange\Form\Util\TemplateManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class ActionManager implements TemplateManagerInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Action::class;

    /**
     * getList
     *
     * @return array
     * @throws \Exception
     */
    public function getList(): array
    {
        return $this->getRepository()->createQueryBuilder('a')
            ->select('a.id', 'a.name', 'a.allowedCategories', 'a.groupBy', 'r.nameShort')
            ->leftJoin('a.role', 'r')
            ->orderBy('a.groupBy', 'ASC')
            ->addOrderBy('a.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * getAllRoles
     *
     * @return array
     * @throws \Exception
     */
    public function getAllRoles(): array
    {
        return $this->getRepository(PersonRole::class)->findBy([], ['nameShort' => 'ASC']);
    }

    /**
     * getAllExistingRoles
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function getAllExistingRoles(int $id): array
    {
        $action = $this->find($id);
        $results = new ArrayCollection();
        $results->add($action->getRole());
        foreach ($action->getPersonRoles()->getIterator() as $role)
            if (!$results->contains($role))
                $results->add($role);

        $iterator = $results->getIterator();

        $iterator->uasort(
            function ($a, $b) {
                return ($a->getNameShort() < $b->getNameShort()) ? -1 : 1;
            }
        );

        return iterator_to_array($iterator, false);
    }

    /**
     * togglePermission
     *
     * @param $role
     * @throws \Exception
     */
    public function togglePermission($role)
    {
        $role = $this->getRepository(PersonRole::class)->findOneByNameShort($role);
        if ($role === $this->entity->getRole() || !in_array($role->getCategory(), $this->entity->getAllowedCategories()))
            return;

        if ($this->entity->getPersonRoles()->contains($role))
            $this->entity->removePersonRole($role);
        else
            $this->entity->addPersonRole($role);

        $this->saveEntity();
        $this->getMessageManager()->add('success', 'action.permission.toggle', ["%{action}" => $this->entity->getName(), '%{permission}' => $role->getName()], 'Security');
        return;
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
     * getTranslationDomain
     *
     * @return string
     */
    public function getTranslationDomain(): string
    {
        return 'Security';
    }

    /**
     * getColumnTemplate
     *
     * @return array
     */
    public function getTemplate(): array
    {
        return [
            'form' => [
                'url' => '/security/permission/{id}/edit/',
                'url_options' => [
                    '{id}' => 'id',
                ],
            ],
            'container' => [
                'panel' => [
                    'label' => 'action.title',
                    'colour' => 'info',
                    'buttons' => [
                        [
                            'type' => 'save',
                        ],
                        [
                            'type' => 'close',
                        ],
                    ],
                    'rows' => [
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'class' => 'col-6 card',
                                    'form' => ['name' => 'row'],
                                ],
                                [
                                    'class' => 'col-6 card',
                                    'form' => ['groupBy' => 'row'],
                                ],
                            ],
                        ],
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'class' => 'col-6 card',
                                    'rows' => [
                                        [
                                            'class' => 'row',
                                            'columns' => [
                                                [
                                                    'form' => ['route' => 'row'],
                                                    'class' => 'col-12',
                                                ],
                                            ],
                                        ],
                                        [
                                            'class' => 'row',
                                            'columns' => [
                                                [
                                                    'form' => ['role' => 'row'],
                                                    'class' => 'col-12',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'class' => 'col-6 card',
                                    'container' => [
                                        'class' => '',
                                        'headerRow' => [
                                            'class' => 'row row-header small',
                                            'columns' => [
                                                [
                                                    'class' => 'col-5 text-center',
                                                    'label' => 'action.route_param.name.label',
                                                ],
                                                [
                                                    'class' => 'col-5 text-center',
                                                    'label' => 'action.route_param.value.label',
                                                ],
                                                [
                                                    'class' => 'col-2 text-center',
                                                    'label' => 'Actions',
                                                ],
                                            ],
                                        ],
                                        'collection' => [
                                            'form' => 'routeParams',
                                            'buttons' => [
                                                'add' => [
                                                    'mergeClass' => 'btn-sm',
                                                    'type' => 'add',
                                                    'style' => [
                                                        'float' => 'right',
                                                    ],
                                                ],
                                                'delete' => [
                                                    'mergeClass' => 'btn-sm',
                                                    'type' => 'delete',
                                                ],
                                            ],
                                            'rows' => [
                                                [
                                                    'class' => 'row row-striped',
                                                    'columns' => [
                                                        [
                                                            'class' => 'col-5',
                                                            'form' => ['name' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-5',
                                                            'form' => ['value' => 'widget'],
                                                        ],
                                                        [
                                                            'class' => 'col-2',
                                                            'collection_actions' => true,
                                                        ],
                                                   ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'class' => 'row',
                            'columns' => [
                                [
                                    'class' => 'col-6 card',
                                    'form' => ['allowedCategories' => 'row'],
                                ],
                                [
                                    'class' => 'col-6 card',
                                    'form' => ['personRoles' => 'row'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
