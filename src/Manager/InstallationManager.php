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
 * Date: 8/06/2018
 * Time: 12:30
 */
namespace App\Manager;


use App\Organism\Database;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;

class InstallationManager
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Database
     */
    private $sql;

    /**
     * InstallationManager constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->sql = new Database();
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Test Connected
     *
     * @param $params ['parameters']
     *
     * @return mixed
     */
    public function isConnected()
    {
        $this->getSQLParameters();
        $this->connection = $this->getConnection(false);

        $this->sql->error = 'No Error Detected.';
        $this->sql->setConnected(true);

        try {
            $this->connection->connect();
        } catch (ConnectionException $e) {
            $this->sql->error = $e->getMessage();
            $this->sql->setConnected(false);
            $this->exception = $e;
        }

        return $this->sql->isConnected();
    }

    /**
     * @param bool $useDatabase
     *
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getConnection($useDatabase = true)
    {
        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = [
            'driver' => $this->sql->getDriver(),
            'host' => $this->sql->getHost(),
            'port' => $this->sql->getPort(),
            'user' => $this->sql->getUser(),
            'password' => $this->sql->getPass(),
            'charset' => $this->sql->getCharset()
        ];
        if ($useDatabase)
            $connectionParams['dbname'] = $this->sql->getName();

        $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        return $this->connection;

    }

    /**
     * Get SQL Parameters
     *
     * @return Database
     */
    public function getSQLParameters(): Database
    {
        $params = file($this->getProjectDir() . '/.env');

        $x = Yaml::parse(file_get_contents($this->getProjectDir() . '/config/packages/doctrine.yaml'));
        $x = $x['parameters'];

        $params = array_merge($params, $x);

        foreach ($params as $name => $value)
            if (strpos($name, 'db_') === 0)
                $params['parameters'][str_replace('db_', '', $name)] = $value;

        $params['parameters'] = array_merge($params['parameters']);

        foreach ($params['parameters'] as $name => $value) {
            $n = 'set' . ucfirst($name);
            $this->sql->$n($value);
        }

        return $this->sql;
    }

    /**
     * getProjectDir
     *
     * @return string
     */
    private function getProjectDir(): string
    {
        return realpath(__DIR__ . '/../../');

    }
}