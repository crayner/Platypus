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
 * Date: 28/09/2018
 * Time: 13:48
 */
namespace App\Manager;

use App\Entity\Setting;
use App\Manager\Settings\SettingCreationInterface;
use App\Organism\SettingCache;
use App\Validator\Regex;
use App\Validator\Twig;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Hillrange\Form\Validator\Integer;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class SettingManager
 * @package App\Manager
 */
class SettingManager implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var object|\Symfony\Bridge\Monolog\Logger
     */
    private $logger;

    /**
     * @var TwigManager
     */
    private $twig;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
    /**
     * SettingManager constructor.
     * @param ContainerInterface $container
     * @param MessageManager $messageManager
     */
    public function __construct(ContainerInterface $container, MessageManager $messageManager, TwigManager $twig)
    {
        $this->setContainer($container);
        new StaffManager($this->getEntityManager());
        $this->messageManager = $messageManager;
        $this->twig = $twig;
        try {
            $this->getEntityManager()->getRepository(Setting::class);
        } catch (ConnectionException $e) {
            $this->setDatabaseFail(true);
        }
        $this->logger = $container->get('monolog.logger.setting');
    }

    /**
     * @param ContainerInterface $container
     * @return SettingManager
     */
    public function setContainer(ContainerInterface $container = null): SettingManager
    {
        $this->container = $container;
        return $this;
    }

    /**
     * getEntityManager
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @var bool
     */
    private $databaseFail = false;

    /**
     * isDatabaseFail
     *
     * @return bool
     */
    public function isDatabaseFail(): bool
    {
        return $this->databaseFail ? true : false;
    }

    /**
     * setDatabaseFail
     *
     * @param bool|null $databaseFail
     * @return SettingManager
     */
    public function setDatabaseFail(?bool $databaseFail): SettingManager
    {
        $this->databaseFail = $databaseFail ? true : false;
        return $this;
    }

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @return MessageManager
     */
    public function getMessageManager(): MessageManager
    {
        return $this->messageManager;
    }

    /**
     * get
     *
     * @param string $name
     * @param null $default
     * @param array $options
     * @return null
     */
    public function get(string $name, $default = null, array $options = [])
    {
        $this->name = $name;
        if ($this->isDatabaseFail()) {
            $this->logger->error(sprintf('The database is not available. Default value returned for setting %s.', $name), [$name, $default, $options]);
            return $default;
        }
        $name = strtolower($name);

        $this->readSession()->getSetting($name);

        if ($this->isValid()) {
            $this->logger->debug(sprintf('The setting %s was found and returned from the cache.', $name), [$name, $default, $options]);
            return $this->getValue($default, $options);
        }
        $this->loadSetting($name);

        if ($this->isValid() && $this->isCorrectName($name)) {
            $this->logger->debug(sprintf('The setting %s was found and returned from the database.', $name), [$name, $default, $options]);
            return $this->getValue($default, $options);
        }
        elseif ($this->isValid() && ! $this->isCorrectName($name)) {
            $this->logger->error(sprintf('The setting %s was found but an issue caused the system to reject the value.', $name), [$name, $default, $options]);
            return $this->get($name, $default, $options);
        }
        return $default;
    }

    /**
     * @return object|\Symfony\Bridge\Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Read Session
     */
    private function readSession(): SettingManager
    {
        if ($this->isLockedCache())
            return $this;
        if ($this->hasSession()) {
            $this->settings = $this->getSession()->get('settings');
            $this->removeInvalidSettings();
        } else
            $this->settings = new ArrayCollection();

        $this->lockedCache = true;
        return $this;
    }

    /**
     * @var bool
     */
    private $lockedCache = false;

    /**
     * @return bool
     */
    public function isLockedCache(): bool
    {
        return $this->lockedCache;
    }

    /**
     * @param bool $lockedCache
     * @return SettingManager
     */
    public function setLockedCache(bool $lockedCache): SettingManager
    {
        $this->lockedCache = $lockedCache;
        return $this;
    }

    /**
     * hasSession
     *
     * @return bool
     */
    public function hasSession(): bool
    {
        return $this->getRequest()->hasSession();
    }

    /**
     * getRequest
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        if (empty($this->request))
            $this->request = $this->getContainer()->get('request_stack')->getCurrentRequest();

        return $this->request;
    }

    /**
     * getSession
     *
     * @return SessionInterface|null
     */
    public function getSession(): ?SessionInterface
    {
        if ($this->hasSession())
            return $this->getRequest()->getSession();
        return null;
    }

    /**
     * removeInvalidSettings
     *
     */
    private function removeInvalidSettings(): SettingManager
    {
        $settings = clone $this->getSettings();

        foreach ($settings as $setting)
            if (! $this->isValid($setting))
                $this->settings->remove($setting->getName());

        return $this;
    }

    /**
     * @var ArrayCollection|null
     */
    private $settings;

    /**
     * @return ArrayCollection
     */
    public function getSettings(): ArrayCollection
    {

        if (empty($this->settings))
            $this->settings = new ArrayCollection();
        return $this->settings;
    }

    /**
     * @var SettingCache
     */
    private $setting;

    /**
     * getSetting
     *
     * @return SettingCache|null
     */
    public function getSetting(string $name): ?SettingCache
    {
        if ($this->isValid() && $name === $this->setting->getSetting()->getName())
            return $this->setting;

        if ($this->getSettings()->containsKey($name))
            $this->setting = $this->settings->get($name);
        else
            $this->setting = null;

        return $this->setting;
    }

    /**
     * isValid
     *
     * @return bool
     */
    private function isValid(?SettingCache $setting = null): bool
    {
        if (empty($setting))
            $setting = $this->setting;

        if (! $setting instanceof SettingCache)
            return false;

        if (! $setting->getSetting() instanceof Setting)
            return false;

        return $setting->isValid();
    }

    /**
     * loadSetting
     *
     * @param string $name
     * @return SettingManager
     */
    private function loadSetting(string $name): SettingManager
    {
        $setting = $this->findOneByName($name);

        if ($setting instanceof SettingCache)
            return $this->addSetting($setting);

        $this->readSession()->getSetting($name);

        return $this;
    }

    /**
     * findOneByName
     *
     * @param string $name
     * @return SettingCache|null
     */
    public function findOneByName(string $name): ?SettingCache
    {
        try {
            $setting = $this->getEntityManager()->getRepository(Setting::class)->findOneByName($name);
        } catch (TableNotFoundException $e) {
            // Continue
            $setting = new Setting();
        }
        $setting = $this->getSettingCache($setting);

        $setting->getValue();

        $this->addSetting($setting, $name);

        return $this->setting;
    }

    /**
     * getSettingCache
     *
     * @param Setting|null $setting
     * @return SettingCache
     */
    private function getSettingCache(?Setting $setting = null): SettingCache
    {
        return new SettingCache($setting);
    }

    /**
     * addSetting
     *
     * @param SettingCache|null $setting
     * @param string $name
     * @return SettingManager
     */
    private function addSetting(?SettingCache $setting, ?string $name = null): SettingManager
    {
        if (empty($setting) || ! $setting->isValid())
            return $this;

        $name = $name ?: $setting->getSetting()->getName();

        $setting->setCacheTime(new \DateTime('now'));

        $this->getSettings();

        $this->settings->set(strtolower($name), $setting);

        $this->setting = $setting;

        return $this->flushToSession();
    }

    /**
     * getParameter
     *
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function getParameter($name, $default = null)
    {
        if ($this->hasParameter($name))
            return $this->getContainer()->getParameter($name);

        if (false === strpos($name, '.'))
            return $default;

        $pName = explode('.', $name);

        $key = array_pop($pName);

        $name = implode('.', $pName);

        $value = $this->getParameter($name, $default);

        if (is_array($value) && isset($value[$key]))
            return $value[$key];

        throw new \InvalidArgumentException(sprintf('The value %s is not a valid array parameter.', $name));
    }

    /**
     * Has parameter
     *
     * @param   string $name
     * @param   mixed $default
     *
     * @return  mixed
     */
    public function hasParameter($name)
    {
        return $this->getContainer()->hasParameter($name);
    }

    /**
     * @var bool
     */
    private $action = false;

    /**
     * @return bool
     */
    public function isAction(): bool
    {
        return $this->action ? true : false;
    }

    /**
     * @param bool $action
     *
     * @return SettingManager
     */
    public function setAction(bool $action): SettingManager
    {
        $this->action = $action ? true : false;

        return $this;
    }

    /**
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     */
    public function buildSystemSettings(): bool
    {
        $current = $this->getSystemVersion();

        $software = VersionManager::VERSION;

        $this->systemSettingsInstalled = true;

        if (version_compare($current, $software, '>='))
            return true;

        $this->systemSettingsInstalled = false;

        if (! $this->isAction())
        {
            $this->getMessageManager()->add('info', 'update.setting.required', ['%{software}' => $software, '%{current}' => $current], 'System');
            return false;
        }

        while (version_compare($current, $software, '<'))
        {
            $current = VersionManager::incrementVersion($current);

            $class = 'App\\Organism\\Settings_' . str_replace('.', '_', $current);

            if (class_exists($class))
            {
                $class = new $class();

                if (!$class instanceof SettingInterface)
                    trigger_error('The setting class ' . $class->getClassName() . ' is not correctly formatted as a SettingInterface.');

                $data = Yaml::parse($class->getSettings());

                if (isset($data['version']))
                    unset($data['version']);

                $count = $this->createSettings($data);
                $this->getMessageManager()->add('success', 'system.setting.file', ['transChoice' => $count, '%{class}' => $class->getClassName()], 'System');
            }

            if (version_compare($current, $software, '='))
                $this->systemSettingsInstalled = true;
        }

        $this->updateCurrentVersion($current);

        return false;
    }

    /**
     * @return string
     */
    public function getSystemVersion(): string
    {
        if ($this->has('version'))
            return $this->get('version', '0.0.00');
        else
            return '0.0.00';
    }

    /**
     * has
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        $this->get($name);
        return $this->isValid();
    }

    /**
     * createSettings
     *
     * @param array $data
     * @return int
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createSettings(array $data = []): int
    {

        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'name',
                'display_name',
                'setting_type',
                'description',
            ]
        );
        $resolver->setDefaults(
            [
                'value' => null,
                'validators' => null,
                'id' => null,
            ]
        );

        $create = $this->getRequest()->request->get('create');
        $data = $create ? Yaml::parse($create['setting']) : $data;
        $this->settings = new ArrayCollection();


        $settings = $this->getEntityManager()->getRepository(Setting::class)->createQueryBuilder('s','s.name')
            ->select('s.name,s.id')
            ->getQuery()
            ->getArrayResult();

        $insert = 0;
        $update = 0;
        $conn = $this->getEntityManager()->getConnection();
        $tableName = $this->getEntityManager()->getClassMetadata(Setting::class)->table['name'];
        $this->beginTransaction();
        foreach ($data as $values) {
            $values = $resolver->resolve($values);
            $name = $values['name'];
            if (isset($settings[$name]))
            {
                $w['id'] = $settings[$name]['id'];
                $values['id']= $w['id'];
                $conn->update($tableName, $values, $w);
                $update++;
            } else {
                $conn->insert($tableName, $values);
                $insert++;
            }
        }
        $this->commit();
        $this->getMessageManager()->add('success', '{0}No settings were installed.|{1}A setting was added to the database.|]1,Inf[%count% settings were installed successfully.', ['transChoice' => $insert], 'System');
        $this->getMessageManager()->add('success', '{0}No settings were altered.|{1}A setting was altered in the database.|]1,Inf[%count% settings were altered successfully.', ['transChoice' => $update], 'System');
        return $insert + $update;
    }

    /**
     * setForeignKeyChecksOff
     *
     */
    public function setForeignKeyChecksOff(): void
    {
        $this->getEntityManager()->getConnection()->query('SET FOREIGN_KEY_CHECKS = 0');
    }

    /**
     * setForeignKeyChecksOn
     *
     */
    public function setForeignKeyChecksOn(): void
    {
        $this->getEntityManager()->getConnection()->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * beginTransaction
     *
     * @param bool $foreignKeyCheckOff
     */
    public function beginTransaction(bool $foreignKeyCheckOff = false): void
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
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
        $this->getEntityManager()->getConnection()->commit();
        if ($foreignKeyCheckOn)
            $this->setForeignKeyChecksOn();
    }

    /**
     * @param $current
     * @throws \Doctrine\ORM\ORMException
     */
    private function updateCurrentVersion($current)
    {
        $version = [];
        $data = [];
        $version['setting_type'] = 'system';
        $version['display_name'] = 'System Version';
        $version['description'] = 'The version of Busybee currently configured on your system.';
        $version['value'] = $current;
        $version['name'] = 'version';
        $data['version'] = $version;
        $this->createSettings($data);
    }

    /**
     * set Setting
     *
     * @version 31st October 2016
     * @since   21st October 2016
     * @param $name
     * @param $value
     * @return SettingManager
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function set($name, $value): SettingManager
    {
        $name = strtolower($name);
        $this->get($name);

        if (!$this->isValid())
            return $this;

        $setting = $this->getEntityManager()->getRepository(Setting::class)->findOneByName($this->setting->getSetting()->getName());

        $this->getEntityManager()->refresh($setting);
        $this->setting = $this->getSettingCache($setting);

        if ($x = $this->setting->setValue($value)
                ->writeSetting($this->getEntityManager(), $this->getValidator(), $this->getConstraints($this->setting->getSetting()->getSettingType())) !== true)
            if (is_iterable($x))
                foreach($x->getIterator() as $constraintViolation)
                    $this->getMessageManager()->add('danger', $constraintViolation->getMessage(), [], false);

        unset($this->settings[$name]);

        return $this->removeSetting($this->setting)->addSetting($this->setting);
    }

    /**
     * isCorrectName
     *
     * @param $name
     * @return bool
     */
    public function isCorrectName($name): bool
    {
        if ($name === $this->setting->getSetting()->getName())
            return true;

        $this->settings = null;
        $this->setting = null;

        if($this->hasSession())
            $this->getSession()->set('settings', $this->getSettings());

        return false;
    }

    /**
     * flushToSession
     *
     * @param Setting $setting
     */
    private function flushToSession(): SettingManager
    {
        if ($this->hasSession())
            $this->getSession()->set('settings', $this->getSettings());
        return $this;
    }

    /**
     * getValue
     *
     * @param null $default
     * @param array $options
     * @return array|mixed|null|string
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    private function getValue($default = null, array $options = [])
    {
        switch ($this->setting->getSetting()->getSettingType()) {
            case 'twig':
                $value = null;
                try {
                    return $this->twig->getTwig()->createTemplate($this->setting->getFinalValue($default))->render($options);
                } catch (\Twig_Error_Syntax $e) {
                    throw $e;
                } catch (\Twig_Error_Runtime $e) {
                    // Ignore Runtime Errors, and return raw twig value
                    return $this->setting->getFinalValue($default);
                }
                break;
            case 'array':
                return $this->setting->getFinalValue($default);
                break;
            default:
                return $this->setting->getFinalValue($default);
        }
    }

    /**
     * @var bool
     */
    private $installMode = false;

    /**
     * @return bool
     */
    public function isInstallMode(): bool
    {
        return $this->installMode = $this->installMode ? true : false ;
    }

    /**
     * @param bool $installMode
     * @return SettingManager
     */
    public function setInstallMode(?bool $installMode): SettingManager
    {
        $this->installMode = $installMode ? true : false ;
        return $this;
    }

    /**
     * getValidator
     *
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return Validation::createValidator();
    }

    /**
     * getConstraints
     *
     * @param string $settingType
     * @return array
     */
    public function getConstraints(string $settingType): array
    {
        $constraints = [];
        $constraints['boolean'] = [];
        $constraints['integer'] = [
            new Integer(),
        ];
        $constraint['image'] = [];
        $constraints['file'] = [];
        $constraints['array'] = [
            new \App\Validator\Yaml(),
        ];
        $constraints['twig'] = [
            new Twig(),
        ];
        $constraints['system'] = [];
        $constraints['string'] = [];
        $constraints['enum'] = [];  //SettingChoiceSettingType should be used as it adds a Setting Choice Validator
        $constraints['regex'] = [
            new Regex(),
        ];
        $constraints['text'] = [];
        $constraints['time'] = [];

        if (isset($constraints[$settingType]))
            return $constraints[$settingType];
        return [];
    }

    /**
     * removeSetting
     *
     * @param SettingCache|null $setting
     * @return SettingManager
     */
    private function removeSetting(?SettingCache $setting): SettingManager
    {
        if (! $setting instanceof SettingCache)
            return $this;

        $this->getSettings()->remove($setting->getSetting()->getName());

        return $this->flushToSession();
    }

    /**
     * createSettingDefinition
     *
     * @param string $name
     * @param array $options
     * @return SettingCreationInterface
     */
    public function createSettingDefinition(string $name, array $options = []): SettingCreationInterface
    {
        $class = 'App\\Manager\\Settings\\' . $name . 'Settings';
        if (! class_exists($class))
            trigger_error('The class ' . $class . ' does not exist,');

        $class = new $class($options);

        if (! $class instanceof SettingCreationInterface)
            trigger_error('The class ' . get_class($class) . ' does not implement the ' . SettingCreationInterface::class);

        if ($name !== $class->getName())
            trigger_error('The class ' . get_class($class) . ' does not match the setting\'s name: '.$name);

        return $class->getSettings($this);
    }

    /**
     * createOneByName
     *
     * @param string $name
     * @return SettingCache|null
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createOneByName(string $name): Setting
    {
        $sss = $this->findOneByName($name);

        if ($sss !== null && $sss->getSetting()->getName() === $name) {
            $this->addSetting($sss);
            return $sss->getSetting();
        }

        $setting = new Setting();
        $setting->setName($name);

        return $setting;
    }

    /**
     * createSetting
     * @param Setting $setting
     * @return SettingManager
     * @throws \Doctrine\DBAL\Exception\TableNotFoundException
     * @throws \Throwable
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Syntax
     */
    public function createSetting(Setting $setting): SettingManager
    {
        if ($setting->getSettingType() === 'array')
            $setting->setValue(str_replace(array("\\r", "\\n", '"'), array('', "\n", ''),$setting->getValue()));

        $this->getEntityManager()->persist($setting);
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * setParameter
     *
     * @param array $name
     * @param $value
     * @return mixed
     */
    public function setParameter(array $name, $value)
    {
        $path = $this->getContainer()->get('kernel')->getProjectDir() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'packages' . DIRECTORY_SEPARATOR . 'platypus.yaml';
        $content = Yaml::parse(file_get_contents($path));

        $content['parameters'] = $this->changeParameterValue($content['parameters'], $name, $value);
        try{
            file_put_contents($path, Yaml::dump($content));
        } catch (\ErrorException $e) {
            throw new \ErrorException(sprintf("The file '%s' permissions are not set to allow the file to be written. This needs to be corrected on the server before the settings can be changed.", $path));
        }
    }

    /**
     * changeParameterValue
     *
     * @param array $parameters
     * @param array $name
     * @param mixed $value
     * @return array
     */
    private function changeParameterValue(array $parameters, array $name, $value): array
    {
        $key = array_shift($name);

        if (empty($name))
        {
            $parameters[$key] = $value;
            return $parameters;
        }

        if (empty($parameters[$key]))
            $parameters[$key] = [];

        $parameters[$key] = $this->changeParameterValue($parameters[$key], $name, $value);

        return $parameters;
    }

    public function getLocale(): string
    {
        $locale = $this->getRequest()->get('_locale');
        if (empty($locale))
            $locale = $this->getParameter('locale');
        return $locale ?: 'en';
    }
}