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

use Doctrine\Common\Persistence\ObjectManager;
use Hillrange\Security\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class UserFixtures implements DummyDataInterface
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
        $this->setLogger($logger)->setObjectManager($manager)->setMetaData(User::class);
        // Bundle to manage file and directories
        $data = Yaml::parse(file_get_contents(__DIR__ . '/Data/hrs_user.yml'));

        $user = $manager->getConnection()->fetchAssoc('SELECT * FROM `'.$this->tableName.'` WHERE id = 1');

        $this->setMetaData(User::class)->truncateTable()->buildTable($data);

        if(is_array($user)) {
            $this->beginTransaction(true);
            $this->getConnection()->update($this->tableName, $user, ['id' => 1]);
            $this->commit();
        }
    }
}
