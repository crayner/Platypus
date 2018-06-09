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
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Exception\DumpException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class InstallationManager
{

    /**
     * System Requirements
     */
    public static $systemRequirements = [
        'php' 			=> '7.1.0',
        'mysql' 		=> '5.7',
        'extensions' 	=> ['gettext', 'mbstring', 'curl', 'zip', 'xml', 'gd'],
        'settings' 		=> [
            ['max_input_vars', '>=', 5000],
            ['max_file_uploads', '>=', 20],
            ['allow_url_fopen', '==', 1],
            ['register_globals', '==', 0],
        ],
    ];

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Database
     */
    private $sql;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * InstallationManager constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router, MessageManager $messageManager)
    {
        $this->router = $router;
        $this->messageManager = $messageManager;
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
     * @return Connection
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

    /**
     * @var int
     */
    private $step = 0;

    /**
     * @return int
     */
    public function getStep(): int
    {
        return $this->step;
    }

    /**
     * @param int $step
     * @return InstallationManager
     */
    public function setStep(int $step): InstallationManager
    {
        $this->step = $step;
        return $this;
    }

    /**
     * @var array
     */
    private $status = [];

    /**
     * @return array
     */
    public function getStatus(): array
    {
        if (empty($this->status))
            $this->status = [];

        return $this->status;
    }

    /**
     * addStatus
     *
     * @param string $level
     * @param string $message
     * @param array $options
     * @return InstallationManager
     */
    public function addStatus(string $level, string $message, array $options = []): InstallationManager
    {
        $status                 = new \stdClass();
        $status->level          = $level;
        $status->options        = $options;
        $status->message        = $message;
        $this->getStatus();
        $this->status[]         = $status;
        return $this;
    }

    /**
     * @var bool
     */
    private $canInstall = true;

    /**
     * @return bool
     */
    public function isCanInstall(): bool
    {
        return $this->canInstall;
    }

    /**
     * @param bool $canInstall
     * @return InstallationManager
     */
    public function setCanInstall(): InstallationManager
    {
        if (is_writable($this->getProjectDir() . '/config/packages/doctrine.yaml') && is_writable($this->getProjectDir() . '/config/packages/platypus.yaml')) {
            $this->addStatus('success', 'installer.file.permission.success');
            $canInstall = true;
        } else {
            $this->addStatus('danger', 'installer.file.permission.danger');
            $canInstall = false;
        }
        $this->canInstall = $canInstall ? true : false ;
        return $this;
    }

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }

    public function getRequirementCheck(): array
    {
        $results = [];
        $result = new \stdClass();
        $result->label = 'version.name';
        $result->labelKey = ['%{name}' => 'PHP'];
        $result->description = 'version.description';
        $result->descriptionKey = ['%{name}' => 'PHP', '%{version}' => self::$systemRequirements['php']];
        $result->value = phpversion();
        $result->button = ['value' => version_compare(phpversion(), self::$systemRequirements['php'], '>='), 'style' => '', 'on' => ['class' => 'btn btn-success fas fa-check'], 'off' => ['class' => 'btn btn-danger fas fa-times']];
        $results[] = $result;

        $result = new \stdClass();
        $result->label = 'mysql.pdo.support';
        $result->labelKey = [];
        $result->description = '';
        $result->descriptionKey = [];
        $result->value = (extension_loaded('pdo') && extension_loaded('pdo_mysql')) ? 'Installed' : 'Not Installed';
        $result->button = ['value' => (extension_loaded('pdo') && extension_loaded('pdo_mysql')), 'style' => '', 'on' => ['class' => 'btn btn-success fas fa-check'], 'off' => ['class' => 'btn btn-danger fas fa-times']];
        $results[] = $result;


        foreach (self::$systemRequirements['extensions'] as $name){
            $result = new \stdClass();
            $result->label = 'extension.name';
            $result->labelKey = ['%{name}' => $name];
            $result->description = '';
            $result->descriptionKey = [];
            $result->value = extension_loaded($name) ? 'Installed' : 'Not Installed';
            $result->button = ['value' => extension_loaded($name), 'style' => '', 'on' => ['class' => 'btn btn-success fas fa-check'], 'off' => ['class' => 'btn btn-danger fas fa-times']];
            $results[] = $result;

        }

        return $results;
    }

    /**
     * saveLanguage
     *
     * @param Request $request
     * @return bool
     */
    public function saveLanguage(Request $request): bool
    {
        $locale = $request->request->get('install_language');
        $locale = $locale['language'];
        try{
            $contents = Yaml::parse(file_get_contents($this->getProjectDir().'/config/packages/platypus.yaml'));
            $contents['parameters']['locale'] = $locale;
            file_put_contents($this->getProjectDir().'/config/packages/platypus.yaml', Yaml::dump($contents, 4));
        } catch (ParseException $e) {
            $this->addStatus('danger', 'installer.file.parse.error', ['%{name}' => 'platypus.yaml']);
            return false;
        } catch (DumpException $e) {
            $this->addStatus('danger', 'installer.file.dump.error', ['%{name}' => 'platypus.yaml']);
            return false;
        } catch (\ErrorException $e) {
            $this->addStatus('danger', 'installer.file.write.error', ['%{name}' => 'platypus.yaml']);
            return false;
        }

        return true;
    }

    /**
     * Save SQL Parameters
     *
     * @param $params array
     *
     * @return bool
     */
    public function saveSQLParameters(Database $sql): bool
    {
        try{
            $params = Yaml::parse(file_get_contents($this->getProjectDir() . '/config/packages/doctrine.yaml'));

            $params['parameters']['db_driver'] = $sql->getDriver();
            $params['parameters']['db_host'] = $sql->getHost();
            $params['parameters']['db_port'] = $sql->getPort();
            $params['parameters']['db_name'] = $sql->getName();
            $params['parameters']['db_user'] = $sql->getUser();
            $params['parameters']['db_pass'] = $sql->getPass();
            $params['parameters']['db_prefix'] = $sql->getPrefix();
            $params['parameters']['db_server'] = $sql->getServer();

            if (file_put_contents($this->getProjectDir() . '/config/packages/doctrine.yaml', Yaml::dump($params, 8))) {
                $env = file($this->getProjectDir() . '/.env');
                foreach ($env as $q => $w) {
                    if (strpos($w, 'DATABASE_URL=') === 0)
                        $env[$q] = $sql->getUrl();
                    $env[$q] = trim($env[$q]);
                }
                $env = implode($env, "\r\n");
                return file_put_contents($this->getProjectDir() . '/.env', $env);
            }
        } catch (ParseException $e) {
            $this->addStatus('danger', 'installer.file.parse.error', ['%{name}' => 'doctrine.yaml']);
            return false;
        } catch (DumpException $e) {
            $this->addStatus('danger', 'installer.file.dump.error', ['%{name}' => 'doctrine.yaml']);
            return false;
        } catch (\ErrorException $e) {
            $this->addStatus('danger', 'installer.file.write.error', ['%{name}' => 'doctrine.yaml / .env']);
            return false;
        }

        return true;
    }
}