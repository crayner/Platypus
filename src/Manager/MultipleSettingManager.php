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
 * Date: 16/06/2018
 * Time: 14:34
 */
namespace App\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultipleSettingManager
{
    /**
     * @var CollectionManager
     */
    private $collectionManager;

    /**
     * MultipleSettingManager constructor.
     * @param CollectionManager $collectionManager
     */
    public function __construct(CollectionManager $collectionManager)
    {
        $this->collectionManager = $collectionManager;
    }

    /**
     * @var array
     */
    private $sections;

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections ?: [];
    }

    /**
     * @param array $section
     * @return MultipleSettingManager
     */
    public function setSection(array $sections): MultipleSettingManager
    {
        $this->sections = $sections ?: [];

        return $this;
    }

    /**
     * addSection
     *
     * @param array $section
     * @return MultipleSettingManager
     */
    public function addSection(array $section): MultipleSettingManager
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired([
            'settings',
            'name',
        ]);
        $resolver->setDefaults([
            'name_parameters' => [],
            'description' => null,
            'description_parameters' => [],
        ]);
        $section = $resolver->resolve($section);

        $this->getSections();
        $this->sections[$this->safeName($section['name'])]  = $section;
        return $this;
    }

    /**
     * @var ArrayCollection
     */
    private $collections;

    /**
     * getNewCollection
     *
     * @param $section
     * @return CollectionManager
     */
    public function getNewCollection($section): CollectionManager
    {
        $collectionManager = clone $this->collectionManager;
        foreach($section['settings'] as $setting)
            $collectionManager->addEntity($setting);

        return $this->addCollection($section['name'], $collectionManager)
            ->__get($section['name']);
    }

    /**
     * @return ArrayCollection
     */
    public function getCollections(): ArrayCollection
    {
        return $this->collections = $this->collections ?: new ArrayCollection();
    }

    /**
     * @param ArrayCollection $collections
     * @return MultipleSettingManager
     */
    public function setCollections(ArrayCollection $collections): MultipleSettingManager
    {
        $this->collections = $collections ?: null;
        return $this;
    }

    /**
     * addCollection
     *
     * @param string $name
     * @param CollectionManager $collection
     * @return MultipleSettingManager
     */
    public function addCollection(string $name, CollectionManager $collection): MultipleSettingManager
    {
        $this->getCollections()->set($this->safeName($name), $collection);

        return $this;
    }

    /**
     * __get
     *
     * @param $name
     * @return CollectionManager
     */
    public function __get($name): CollectionManager
    {
        return $this->getCollections()->get($this->safeName($name));
    }

    /**
     * @var string
     */
    private $header;

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return MultipleSettingManager
     */
    public function setHeader(string $header): MultipleSettingManager
    {
        $this->header = $header;
        return $this;
    }

    public function safeName($name): string
    {
        return strtolower(str_replace([' '], '_', trim($name)));
    }

    /**
     * saveSections
     *
     */
    public function saveSections(SettingManager $sm)
    {
        foreach ($this->getSections() as $section)
            foreach ($section['settings'] as $setting)
                $sm->createSetting($setting->getSetting());
    }
}