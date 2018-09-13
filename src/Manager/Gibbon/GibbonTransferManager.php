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
 * Date: 12/09/2018
 * Time: 11:29
 */
namespace App\Manager\Gibbon;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Psr\Log\LoggerInterface;

/**
 * Class GibbonTransferManager
 * @package App\Manager\Gibbon
 */
class GibbonTransferManager implements GibbonTransferInterface
{
    /**
     * @return string
     */
    public function getGibbonName(): string
    {
        return $this->gibbonName;
    }

    /**
     * @param string $gibbonName
     */
    public function setGibbonName(string $gibbonName): void
    {
        $this->gibbonName = $gibbonName;
    }

    /**
     * getEntityName
     *
     * @return array
     */
    public function getEntityName(): array
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName(string $entityName): void
    {
        $this->entityName = $entityName;
    }

    /**
     * @return array
     */
    public function getTransferRules(): array
    {
        return $this->transferRules;
    }

    /**
     * @param array $transferRules
     */
    public function setTransferRules(array $transferRules): void
    {
        $this->transferRules = $transferRules;
    }

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @return ObjectManager
     */
    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     * @return GibbonTransferManager
     */
    public function setObjectManager(ObjectManager $objectManager): GibbonTransferManager
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    /**
     * writeEntityRecords
     *
     * @param string $entityName
     * @param array $records
     */
    public function writeEntityRecords(string $entityName, array $records)
    {
        $count = 0;
        $metaData = $this->getObjectManager()->getClassMetadata($entityName);
        $this->objectManager->getConnection()->beginTransaction();
        $this->objectManager->getConnection()->query('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($records as $item) {
            try {
                $this->getObjectManager()->getConnection()->insert($metaData->table['name'], $item);
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->getLogger()->addError('The table row in ' . $metaData->table['name'] . ' encountered a foreign key error: ' . $e->getMessage(), $item);
                $count--;
            } catch (InvalidFieldNameException $e) {
                $this->getLogger()->addError('The table row in ' . $metaData->table['name'] . ' has an invalid field name: ' . $e->getMessage(), $item);
                $count--;
            } catch (UniqueConstraintViolationException $e) {
                $this->getLogger()->addError('The table row in ' . $metaData->table['name'] . ' was not unique: ' . $e->getMessage(), $item);
                $count--;
            }
            $count++;

            if (($count % 100) == 0)
                $this->getLogger()->addInfo('Actioned ' . $count . ' records for ' . $entityName . ' of a maximum ' . count($records) . ' possible.  Continuing...');
        }

        if ($count === count($records))
            $this->getLogger()->addInfo('Actioned ' . $count . ' records for ' . $entityName . ' of a maximum ' . count($records) . ' possible.');
        else
            $this->getLogger()->addWarning('Actioned ' . $count . ' records for ' . $entityName . ' of a maximum ' . count($records) . ' possible.');

        $this->objectManager->getConnection()->query('SET FOREIGN_KEY_CHECKS = 1');
        $this->objectManager->getConnection()->commit();
    }

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return GibbonTransferManager
     */
    public function setLogger(LoggerInterface $logger): GibbonTransferManager
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * getNextGibbonName
     *
     * @return string
     */
    public function getNextGibbonName(): string
    {
        return $this->nextGibbonName;
    }

}