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
 * Time: 13:21
 */
namespace App\Manager;

use App\Entity\Family;
use App\Entity\FamilyRelationship;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FamilyManager
 * @package App\Manager
 */
class FamilyManager extends TabManager
{
    use EntityTrait;

    /**
     * @var string
     */
    protected $entityName = Family::class;

    /**
     * @var array
     */
    protected $tabs = [
        [
            'name' => 'details',
            'label' => 'family.details.tab',
            'include' => 'Family/details.html.twig',
            'message' => 'familyDetailsMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'adults',
            'label' => 'family.adults.tab',
            'include' => 'Family/adults.html.twig',
            'message' => 'familyAdultsMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'children',
            'label' => 'family.children.tab',
            'include' => 'Family/children.html.twig',
            'message' => 'familyChildrenMessage',
            'translation' => 'Person',
        ],
        [
            'name' => 'relationships',
            'label' => 'Relationships',
            'include' => 'Family/relationship.html.twig',
            'message' => 'familyRelationshipMessage',
            'translation' => 'Person',
            'display' => 'hasRelationships'
        ],
    ];

    /**
     * hasRelationships
     *
     * @return bool
     */
    public function hasRelationships(): bool
    {
        if (empty($this->getEntity()) || $this->getEntity()->getAdultMembers()->count() === 0 || $this->getEntity()->getChildMembers()->count() === 0)
            return false;

        $this->buildRelationships();
        return true;
    }

    /**
     * buildRelationships
     * buildRelationships
     *
     * @return FamilyManager
     */
    private function buildRelationships(): FamilyManager
    {
        foreach($this->getEntity()->getAdultMembers()->getIterator() as $adult)
        {
            foreach($this->getEntity()->getChildMembers()->getIterator() as $child)
            {
                $found = false;
                foreach($this->getEntity()->getRelationships()->getIterator() as $rel)
                    if ($rel->getAdult() == $adult->getPerson() && $rel->getChild() === $child->getPerson())
                    {
                        $found = true;
                        break;
                    }
                    if (! $found) {
                        $rel = new FamilyRelationship();
                        $rel->setFamily($this->getEntity());
                        $rel->setAdult($adult->getPerson());
                        $rel->setChild($child->getPerson());
                        $this->getEntity()->addRelationship($rel);
                    }
            }
        }

        return $this;
    }

    /**
     * suggestFamilyName
     *
     * @param int $id
     * @param TranslatorInterface $translator
     * @return array
     * @throws \Exception
     */
    public function suggestFamilyName(int $id, TranslatorInterface $translator): array
    {
        $result = [
            'name' => '',
            'formalName' => '',
        ];

        $family = $this->find($id);
        if (empty($family) || $family->getId() !== $id)
            return $result;

        $parents = $family->getAdultMembers();
        if ($parents->count() === 0)
            return $result;
        $p1 = $parents->first()->getPerson();
        $result['name'] = $p1->getSurname();
        $formalName = trim($translator->trans('person.title.'.$p1->getTitle(), [], 'Person'). ' ' . $p1->getFirstName());

        if ($parents->count() > 1)
        {
            $p2 = $parents->get(1)->getPerson();
            if ($p1->getSurname() !== $p2->getSurname()) {
                $formalName .= ' ' . $p1->getSurname();
                $result['name'] .= '/' . $p2->getSurname();
            }
            $formalName .= ' and ' . trim($translator->trans('person.title.'.$p2->getTitle(), [], 'Person'). ' ' . $p2->getFirstName() . ' ' . $p2->getSurname());
        } else
            $formalName .= ' ' . $p1->getSurname();

        $result['formalName'] = $formalName;
        $result['name'] .= ' ('.mb_substr($family->getId(), -3).')';
        return $result;
    }
}