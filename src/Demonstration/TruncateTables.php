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
 * Date: 21/05/2018
 * Time: 18:41
 */
namespace App\Demonstration;

use App\Exception\MissingClassException;
use Doctrine\Common\Persistence\ObjectManager;
use Hillrange\Security\Entity\User;

class TruncateTables
{
    /**
     * execute
     *
     * @param ObjectManager $objectManager
     */
    public function execute(ObjectManager $objectManager)
    {
        // initialize the database connection
        $connection = $objectManager->getConnection();
        $schemaManager = $connection->getSchemaManager();
        $tables = $schemaManager->listTables();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS = 0');

            foreach ($tables as $table)
            {
                dump($table);
                if (mb_strpos($table->getName(), '_user') === false) {
                    $sql = $dbPlatform->getTruncateTableSql($table->getName());
                    $connection->executeUpdate($sql);
                } else {
                    $sql = $objectManager->getRepository(User::class)->createQueryBuilder('u')
                        ->delete()
                        ->where('u.id != :uid')
                        ->setParameter('uid', 1)
                        ->getQuery()
                        ->getResult();

                    $sql = sprintf('ALTER TABLE `%s` AUTO_INCREMENT = 1', $table->getName());
                    $connection->exec($sql);
                }
            }

            $connection->query('SET FOREIGN_KEY_CHECKS = 1');
            $connection->commit();
        }
        catch (MissingClassException $e) {
            $connection->rollback();
        }

    }
}
