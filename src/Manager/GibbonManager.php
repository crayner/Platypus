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
 * Time: 11:21
 */
namespace App\Manager;

use App\Exception\MissingClassException;
use App\Manager\Gibbon\GibbonTransferInterface;
use App\Util\StringHelper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\InvalidFieldNameException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Validator\Constraints\ColourValidator;
use Hillrange\Security\Util\ParameterInjector;
use Psr\Log\LoggerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GibbonManager
 * @package App\Manager
 */
class GibbonManager
{
    /**
     * transfer
     *
     * @param string $section
     */
    public function transfer(string $section, LoggerInterface $logger)
    {
        $path = "App\Manager\Gibbon\\".ucfirst($section). 'Manager';

        if (! class_exists($path)) {
            $logger->info(sprintf('No! No! No! %s does not exist.', $section));
            return;
        }

        $this->setTransferManager(new $path());

        foreach ($this->requireBefore() as $w)
            $this->transfer($w, $logger);

        $this->setTransferManager(new $path());

        $this->setLogger($logger);

        $this->getTransferManager()->setObjectManager($this->getObjectManager())->setGibbonEntityManager($this->getGibbonEntityManager());

        $logger->addDebug(sprintf('Started transfer for %s.', $this->getGibbonName()));

        $this->truncate();

        $this->load();

        $logger->addDebug(sprintf('Completed transfer for %s.', $this->getGibbonName()));
    }

    /**
     * execute
     *
     * @param ObjectManager $objectManager
     */
    public function load()
    {
        foreach($this->getEntityName() as $entityName) {
            $this->preLoad($entityName);

            $sql = 'SELECT * FROM `'.$this->getGibbonName().'`';
            $data = $this->getGibbonEntityManager()->getConnection()->fetchAll($sql);

            $records = [];
            $this->joinTables = [];
            foreach ($data as $this->datum) {
                $newData = [];
                foreach ($this->datum as $field => $value)
                    $newData = $this->generateNewFieldData($entityName, $newData, $field, $value);

                $records = $this->postRecord($entityName, $newData, $records, $this->getObjectManager());
            }

            $this->writeEntityRecords($entityName, $records);
            $this->writeJoinTables();

            $this->postLoad($entityName);
        }
    }

    /**
     * writeJoinTables
     *
     */
    private function writeJoinTables()
    {
        foreach($this->joinTables as $name => $data)
        {
            if (! empty($data)) {
                $this->getLogger()->addDebug(sprintf('The join table %s is being actioned.', $this->dbPrefix.$name));

                if (! $this->skipTruncate($name))
                    $this->truncateTable($this->dbPrefix.$name);

                $this->objectManager->getConnection()->beginTransaction();
                $count = 0;
                foreach($data as $item) {
                    $null = false;
                    foreach($item as $nn=>$value) {
                        if ($nn !== 'update' && ! is_integer($value))
                            $value = intval($value);
                        if (empty($value))
                            $null = true;
                    }
                    if ($null)
                        continue;
                    try {
                        if (isset($item['update']) && $item['update']) {
                            $update[$item['update']] = $item[$item['update']];
                            unset($item[$item['update']], $item['update']);
                            $this->getObjectManager()->getConnection()->update($this->dbPrefix . $name, $item, $update);
                        } else {
                            if (isset($item['update']))
                                unset($item['update']);
                            $this->getObjectManager()->getConnection()->insert($this->dbPrefix . $name, $item);
                        }
                    } catch (ForeignKeyConstraintViolationException $e) {
                        $this->getLogger()->addError('The table row in ' . $this->dbPrefix.$name .' encountered a foreign key error: '.$e->getMessage(), $item);
                        $count--;
                    } catch (InvalidFieldNameException $e) {
                        $this->getLogger()->addError('The table row in ' . $this->dbPrefix.$name .' has an invalid field name: '.$e->getMessage(), $item);
                        $count--;
                    } catch (UniqueConstraintViolationException $e) {
                        $this->getLogger()->addError('The table row in ' . $this->dbPrefix.$name .' was not unique: '.$e->getMessage(), $item);
                        $count--;
                    }
                    $count++;

                    if (($count % 100) == 0)
                        $this->getLogger()->addDebug('Actioned ' . $count . ' records for ' . $this->dbPrefix.$name . ' of a maximum ' . count($data) . ' possible.  Continuing...');
                }
                $this->objectManager->getConnection()->commit();
            }
        }

    }
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * GibbonManager constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, ParameterInjector $injector)
    {
        $this->objectManager = $objectManager;
        $this->dbPrefix = $injector->getParameter('db_prefix');
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager(): ObjectManager
    {
        return $this->objectManager;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return GibbonManager
     */
    public function setLogger(LoggerInterface $logger): GibbonManager
    {
        $this->logger = $logger;
        $this->getTransferManager()->setLogger($logger);
        return $this;
    }

    /**
     * execute
     *
     * @param ObjectManager $objectManager
     */
    public function truncate()
    {
        foreach($this->getEntityName() as $entityName) {
            if ($this->skipTruncate($entityName))
                continue;
            $this->preTruncate($entityName);
            $table = $this->objectManager->getClassMetadata($entityName);
            $this->truncateTable($table->table['name']);
            $this->postTruncate($entityName);
        }
    }

    /**
     * truncateTable
     *
     * @param string $tableName
     */
    private function truncateTable(string $tableName)
    {
        $connection = $this->objectManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS = 0');
            $sql = $dbPlatform->getTruncateTableSql($tableName);
            $connection->executeUpdate($sql);
            $connection->query('SET FOREIGN_KEY_CHECKS = 1');
            $connection->commit();
        }
        catch (MissingClassException $e) {
            $connection->rollback();
            $this->getLogger()->addWarning(sprintf('A problem occurred removing the data from ', $tableName));
        }
        $this->getLogger()->addInfo(sprintf('The existing data has been deleted from %s', $tableName));

    }

    /**
     * @var GibbonTransferInterface
     */
    private $transferManager;

    /**
     * @return GibbonTransferInterface
     */
    public function getTransferManager(): GibbonTransferInterface
    {
        return $this->transferManager;
    }

    /**
     * @param GibbonTransferInterface $transferManager
     * @return GibbonManager
     */
    public function setTransferManager(GibbonTransferInterface $transferManager): GibbonManager
    {
        $this->transferManager = $transferManager;
        return $this;
    }

    /**
     * getEntityName
     *
     * @return array
     */
    public function getEntityName(): array
    {
        return $this->getTransferManager()->getEntityName();
    }

    /**
     * getGibbonName
     *
     * @return string
     */
    public function getGibbonName(): string
    {
        return $this->getTransferManager()->getGibbonName();
    }

    /**
     * getFileName
     *
     * @return string
     */
    private function getFileName(): string
    {
        return realpath(__DIR__. '/../Demonstration/Gibbon/'.$this->getGibbonName().'.yml');
    }

    /**
     * @var array
     */
    private $joinTables;

    /**
     * generateNewFieldData
     *
     * @param array $newData
     * @param string $field
     * @param $value
     * @return array
     */
    private function generateNewFieldData(string $entityName, array $newData, string $field, $value): array
    {
        if (empty($field))
            return $newData;

        $resolver = new OptionsResolver();

        $rule = $this->getFieldRule($field);

        $resolver->setRequired(['field',]);
        $resolver->setDefaults([
                'functions' => [],
                'joinTable' => [],
                'combineField' => [],
                'entityName' => $this->getEntityName()[0],
                'link' => [],
            ]);
        $resolver->setAllowedTypes('functions', 'array');
        $resolver->setAllowedTypes('joinTable', 'array');
        $resolver->setAllowedTypes('combineField', 'array');
        $resolver->setAllowedTypes('entityName', 'string');
        $resolver->setAllowedTypes('link', 'array');
        $rule = $resolver->resolve($rule);


        if ($rule['entityName'] !== $entityName)
            foreach($rule['link'] as $link) {
                if ($link['entityName'] === $entityName) {
                    $rule = $resolver->resolve($link);
                    break;
                }
            }

        if ($rule['entityName'] !== $entityName)
            return $newData;

        if (! empty($rule['combineField']))
            $value = $this->handleCombineFields($rule['combineField']);

        foreach($rule['functions'] as $function => $options)
            $value = $this->$function($value, $options);

        if (! empty($rule['joinTable']))
            $this->handleJoinTable($rule['joinTable'], $value, $newData);

        if (! empty($rule['field']))
            $newData[$rule['field']] = $value;

        $newData = $this->postFieldData($entityName, $newData, $field, $value);

        return $newData;
    }

    /**
     * getFieldRule
     *
     * @param $field
     * @return array
     */
    private function getFieldRule($field): array
    {
        $rules = $this->getTransferManager()->getTransferRules();

        if (empty($rules[$field]))
            trigger_error(sprintf('The rule for "%s" is not defined in "%s".', $field, get_class($this->getTransferManager())));

        return $rules[$field];
    }

    /**
     * @return EntityManagerInterface
     */
    public function getGibbonEntityManager(): EntityManagerInterface
    {
        return $this->gibbonEntityManager;
    }

    /**
     * @param EntityManagerInterface $gibbonEntityManager
     * @return GibbonManager
     */
    public function setGibbonEntityManager(EntityManagerInterface $gibbonEntityManager): GibbonManager
    {
        $this->gibbonEntityManager = $gibbonEntityManager;
        return $this;
    }

    /**
     * commaList
     *
     * @param $value
     * @param $options
     * @return array
     */
    private function commaList($value, $options): array
    {
        return explode(',',$value);
    }

    /**
     * integer
     *
     * @param $value
     * @param $options
     * @return int
     */
    private function integer($value, $options): int
    {
        return intval($value);
    }

    /**
     * trim
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function trim($value, $options): string
    {
        return trim($value, $options);
    }

    /**
     * lowercase
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function lowercase($value, $options): string
    {
        return strtolower($value);
    }

    /**
     * length
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function length($value, $options): string
    {
        return mb_substr($value, 0, $options);
    }

    /**
     * datetime
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function datetime($value, $options): string
    {
        return mb_substr($value, 0, 18);
    }

    /**
     * date
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function date($value, $options): string
    {
        return mb_substr($value, 0, 10);
    }

    /**
     * time
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function time($value , $options): string
    {
        return mb_substr($value, 0, 8);
    }

    /**
     * nullable
     *
     * @param $value
     * @return null
     */
    private function nullable($value)
    {
        return empty($value) ? null : $value ;
    }

    /**
     * enum
     *
     * @param $value
     * @return string
     */
    private function enum($value)
    {
        return StringHelper::safeString($value, true) ;
    }

    /**
     * inArray
     *
     * @param $value
     * @param $options
     * @return mixed
     */
    private function inArray($value, $options)
    {
        if (empty($options['default']) || empty($options['choices']))
            return $value;
        return in_array($value, $options['choices']) ? $value : $options['default'] ;
    }

    /**
     * default
     *
     * @param $value
     * @param $options
     * @return mixed
     */
    private function default($value, $options)
    {
        return empty($value) ? $options : $value ;
    }

    /**
     * call
     *
     * @param $value
     * @param $options
     * @return mixed
     */
    private function call($value, $options)
    {
        if (empty($options['function']))
            trigger_error('Call options must have a function');
        $function = $options['function'];

        $options['options'] = array_merge(empty($options['options']) ? [] : $options['options'], ['datum' => $this->datum]);

        return $this->getTransferManager()->$function($value, $options['options'], $this->getObjectManager());
    }

    /**
     * safeString
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function safeString($value, $options): string
    {
        if (is_array($options) && isset($options['removeChars']) && is_array($options['removeChars']))
            $value = str_replace($options['removeChars'], '', $value);

        return StringHelper::safeString($value, true);
    }

    /**
     * safeString
     *
     * @param $value
     * @param $options
     * @return string
     */
    private function colour($value, $options): string
    {
        $colour = ColourValidator::isColour($value, 'hex');

        if ($colour === false)
            return null;
        return $colour;
    }

    /**
     * handleJoinTable
     *
     * @param array $joinTable
     * @param $value
     * @param array $newData
     */
    private function handleJoinTable(array $joinTable, $value, array $newData)
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(
            [
                'call',
                'inverse',
                'join',
                'name'
            ]
        );
        $resolver->setDefaults(
            [
                // default is to install ...
                'update' => false,
            ]
        );

        $joinTable = $resolver->resolve($joinTable);

        if (empty($joinTable['call']['function']))
            trigger_error(sprintf('Call options must have a function on the join Table %s.', $joinTable['name']));
        $function = $joinTable['call']['function'];


        /*
         * The call function should return an array of inverse values.
         * */
        $inverse = $this->getTransferManager()->$function($value, $joinTable['call']['options'] ?: [], $newData);
        $links = [];
        foreach($inverse as $value)
        {
            $result = [];
            $result[$joinTable['inverse']] = $value;
            $result[$joinTable['join']] = $newData['id'];
            if ($joinTable['update'] !== false)
                $result['update'] = $joinTable['update'];
            $links[] = $result;
        }

        $this->joinTables[$joinTable['name']] = array_merge(empty($this->joinTables[$joinTable['name']]) ? [] : $this->joinTables[$joinTable['name']], $links);
    }

    /**
     * handleCombineFields
     *
     * @param $rule
     */
    private function handleCombineFields($rule): array
    {
        $result = [];
        foreach($rule as $field)
            $result[$field] = $this->datum[$field];

        return $result;
    }

    /**
     * boolean
     *
     * @param $value
     * @return int
     */
    private function boolean($value): int
    {
        return in_array(strtoupper($value), ['Y']) ? 1 : 0 ;
    }

    /**
     * inverse_boolean
     *
     * @param $value
     * @return int
     */
    private function inverse_boolean($value): int
    {
        return in_array(strtoupper($value), ['Y']) ? 0 : 1 ;
    }

    /**
     * array
     *
     * @param $value
     * @return string
     */
    private function array($value): string
    {
        if (is_string($value))
            $value = unserialize($value);

        if (empty($value))
            $value = [];

        return serialize($value) ;
    }

    /**
     * json_array
     *
     * @param $value
     * @return string
     */
    private function json_array($value): string
    {
        if (is_string($value))
            $value = explode(',',$value);

        if (empty($value))
            $value = [];

        return json_encode($value) ;
    }

    /**
     * preTruncate
     *
     * @param string $entityName
     */
    private function preTruncate(string $entityName)
    {
        if (! method_exists($this->getTransferManager(), 'preTruncate'))
            return ;
        $this->getTransferManager()->preTruncate($entityName, $this->getObjectManager());
    }

    /**
     * postTruncate
     *
     * @param string $entityName
     */
    private function postTruncate(string $entityName)
    {
        if (! method_exists($this->getTransferManager(), 'postTruncate'))
            return ;
        $this->getTransferManager()->postTruncate($entityName, $this->getObjectManager());
    }

    /**
     * writeEntityRecords
     *
     * @param string $entityName
     * @param array $records
     */
    private function writeEntityRecords(string $entityName, array $records)
    {
        $this->getTransferManager()->writeEntityRecords($entityName, $records);
    }

    /**
     * preLoad
     *
     * @param string $entityName\
     */
    private function preLoad(string $entityName)
    {
        if (! method_exists($this->getTransferManager(), 'preLoad'))
            return ;
        $this->getTransferManager()->preLoad($entityName, $this->getObjectManager());
    }

    /**
     * postLoad
     *
     * @param string $entityName
     */
    private function postLoad(string $entityName)
    {
        if (! method_exists($this->getTransferManager(), 'postLoad'))
            return ;
        $this->getTransferManager()->postLoad($entityName);
    }

    /**
     * postFieldData
     *
     * @param string $entityName
     * @param array $newData
     * @param string $field
     * @param $value
     * @return array
     */
    private function postFieldData(string $entityName, array $newData, string $field, $value): array
    {
        if (! method_exists($this->getTransferManager(), 'postFieldData'))
            return $newData;
        return $this->getTransferManager()->postFieldData($entityName, $newData, $field, $value);
    }

    /**
     * getNextGibbonName
     *
     * @return string
     */
    public function getNextGibbonName(): string
    {
        return $this->getTransferManager()->getNextGibbonName();
    }

    /**
     * postRecord
     *
     * @param string $entityName
     * @param array $newData
     * @return array
     */
    private function postRecord(string $entityName, array $newData, array $records): array
    {
        if (! method_exists($this->getTransferManager(), 'postRecord')) {
            $records[] = $newData;
            return $records;
        }
        return $this->getTransferManager()->postRecord($entityName, $newData, $records, $this->getObjectManager());
    }

    /**
     * skipTruncate
     *
     * @param string $entityName
     * @return bool
     */
    private function skipTruncate(string $entityName): bool
    {
        if (! property_exists($this->getTransferManager(), 'skipTruncate'))
            return false;

        $skipTruncate = $this->getTransferManager()->skipTruncate;
        if (empty($skipTruncate[$entityName]))
            return false;

        if ($skipTruncate[$entityName])
            return true;

        return false;
    }

    /**
     * @var EntityManagerInterface
     */
    private $gibbonEntityManager;

    /**
     * requireBefore
     *
     * @return array
     */
    private function requireBefore(): array
    {
        return $this->getTransferManager()->getRequireBefore();
    }
}