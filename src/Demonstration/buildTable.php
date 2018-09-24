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
 * Time: 14:52
 */
namespace App\Demonstration;

use App\Exception\MissingClassException;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;

trait buildTable
{

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return ObjectManager
     */
    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     * @return buildTable
     */
    public function setObjectManager(ObjectManager $objectManager): DummyDataInterface
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return buildTable
     */
    public function setLogger(LoggerInterface $logger): DummyDataInterface
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * buildTable
     *
     * @param array $data
     * @return $this
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function buildTable(array $data)
    {
        $count = 0;
        $this->getLogger()->addInfo($this->entityName . ' load started.');
        $metaData = $this->getObjectManager()->getClassMetadata($this->entityName);
        foreach ($data as $item)
        {
            try {
                $this->getConnection()->insert($this->tableName, $item);
            } catch (ForeignKeyConstraintViolationException $e)
            {
                $this->getLogger()->addError('The table row in ' . $this->tableName .' encountered an error: '.$e->getMessage(), $item);
            } catch (InvalidFieldNameException $e) {
                $this->getLogger()->addError('The table row in ' . $this->tableName .' encountered an error: '.$e->getMessage(), $item);
            } catch (UniqueConstraintViolationException $e) {
                $this->getLogger()->addError('The table row in ' . $this->tableName .' encountered an error: '.$e->getMessage(), $item);
            }
            $count++;

            if (($count % 100) == 0)
                $this->getLogger()->addInfo('Actioned ' . $count . ' records for ' . $this->entityName . ' of a maximum ' . count($data) . ' possible.  Continuing...');
        }

        if ($count === count($data))
            $this->getLogger()->addInfo('Actioned ' . $count . ' records for ' . $this->entityName . ' of a maximum ' . count($data) . ' possible.');
        else
            $this->getLogger()->addWarning('Actioned ' . $count . ' records for ' . $this->entityName . ' of a maximum ' . count($data) . ' possible.');
        return $this;
    }

    /**
     * buildJoinTable
     *
     * @param array $data
     * @param string $parentName
     * @param string $childName
     * @param string $parentFieldName
     * @param string $childFieldName
     * @param string $method
     */
    protected function buildJoinTable(array $data, string $parentName, string $childName, string $parentFieldName, string $childFieldName, string $method)
    {
        $count = 0;
        $this->getLogger()->addInfo($parentName . ' to ' . $childName . ' load started.');
        foreach ($data as $item) {
            $metaData = $this->getObjectManager()->getClassMetadata($parentName);

            $parent = $this->getObjectManager()->getRepository($parentName)->find($item[$parentFieldName]);
            $child = $this->getObjectManager()->getRepository($childName)->find($item[$childFieldName]);

            if ($parent && $child && method_exists($parent, $method)) {
                $parent->$method($child);

                $this->getObjectManager()->persist($parent);
                $count++;
            } else {
                $r = '';
                foreach ($item as $q => $w)
                    $r .= $q . ': ' . $w . ', ';
                $this->getLogger()->addError('The link failed for some reason: ' . trim($r, ', '));
            }
        }

        $this->getLogger()->addInfo('Added ' . $count . ' ' . $childName . ' to ' . $parentName . ' of a maximum ' . count($data) . ' possible.');
        $this->getObjectManager()->flush();
    }

    /**
     * setForeignKeyChecksOff
     *
     */
    public function setForeignKeyChecksOff(): void
    {
        $this->getObjectManager()->getConnection()->query('SET FOREIGN_KEY_CHECKS = 0');
    }

    /**
     * setForeignKeyChecksOn
     *
     */
    public function setForeignKeyChecksOn(): void
    {
        $this->getObjectManager()->getConnection()->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * beginTransaction
     *
     * @param bool $foreignKeyCheckOff
     */
    public function beginTransaction(bool $foreignKeyCheckOff = false): void
    {
        $this->getObjectManager()->getConnection()->beginTransaction();
        if ($foreignKeyCheckOff)
            $this->setForeignKeyChecksOff();
    }

    /**
     * commit
     *
     * @param bool $foreignKeyCheckOn
     */
    public function commit(bool $foreignKeyCheckOn = true): void
    {
        $this->getObjectManager()->getConnection()->commit();
        if ($foreignKeyCheckOn)
            $this->setForeignKeyChecksOn();
    }

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $tableName;

    /**
     * setMetaData
     *
     * @param $entityName
     */
    private function setMetaData($entityName): DummyDataInterface
    {
        $this->entityName = $entityName;
        $metaData = $this->getObjectManager()->getClassMetadata($entityName);

        $this->tableName = $metaData->table['name'];
        return $this;
    }


    /**
     * truncateTable
     *
     * @return DummyDataInterface
     */
    private function truncateTable(): DummyDataInterface
    {
        $dbPlatform = $this->getObjectManager()->getConnection()->getDatabasePlatform();
        $this->beginTransaction(true);
        try {
            $sql = $dbPlatform->getTruncateTableSql($this->tableName);
            $this->getObjectManager()->getConnection()->executeUpdate($sql);
            $this->commit();
        }
        catch (MissingClassException $e) {
            $this->getObjectManager()->getConnection()->rollback();
            $this->getLogger()->addWarning(sprintf('A problem occurred removing the data from ', $this->tableName));
        }
        $this->getLogger()->addInfo(sprintf('The existing data has been deleted from %s', $this->tableName));
        return $this;
    }

    /**
     * getConnection
     *
     * @return Connection
     */
    private function getConnection(): Connection
    {
        return $this->getObjectManager()->getConnection();
    }
}
