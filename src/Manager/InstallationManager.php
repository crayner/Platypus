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


use Doctrine\DBAL\ConnectionException;
use Symfony\Component\Routing\RouterInterface;

class InstallationManager
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * InstallationManager constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
}