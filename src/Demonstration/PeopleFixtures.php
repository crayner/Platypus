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
 * Date: 20/05/2018
 * Time: 10:57
 */
namespace App\Demonstration;

use App\Entity\Family;
use App\Entity\FamilyMember;
use App\Entity\Person;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class PeopleFixtures implements DummyDataInterface
{
    use buildTable;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     * @param LoggerInterface $logger
     */
    public function load(ObjectManager $manager, LoggerInterface $logger)
    {
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/person.yml'));

        $this->setLogger($logger)->buildTable($data, Person::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/family.yml'));

        $this->buildTable($data, Family::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/family_adult.yml'));

        $this->buildTable($data, FamilyMember::class, $manager);

        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/family_child.yml'));

        $this->buildTable($data, FamilyMember::class, $manager);
    }
}