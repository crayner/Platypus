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
 * Time: 12:22
 */
namespace App\Listener;

use App\Manager\InstallationManager;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InstallSubscriber implements EventSubscriberInterface
{
    /**
     * @var bool
     */
    private static $installing = false;

    /**
     * getSubscribedEvents
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['installationCheck', 4],
            KernelEvents::EXCEPTION => ['exceptionCheck', 8],
        ];
    }

    /**
     * @return bool
     */
    public static function isInstalling(): bool
    {
        return self::$installing;
    }

    /**
     * @var GetResponseEvent
     */
    private $event;

    /**
     * installationCheck
     *
     * @param GetResponseEvent $event
     */
    public function installationCheck(GetResponseEvent $event)
    {
        if ($this->isInstallingRoute($event))
            return ;

        $doFullCheck = true;
        if ($event->getRequest()->hasSession()) {
            if ($event->getRequest()->getSession()->has('installation_check') && strtotime('now') < $event->getRequest()->getSession()->get('installation_check'))
                $doFullCheck = false;
        }


        // Test for db installation.
        $response = null;

        // Are the database settings correct?
        if (! $this->getInstallationManager()->isConnected())
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_start'));
        elseif (! $this->getInstallationManager()->hasDatabase()) // Can I connect to the database?
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_database_create'));
        elseif (! $this->getInstallationManager()->hasDatabaseTables(true, $doFullCheck)) // Have the tables been built?
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_database_create'));
        elseif (! $this->getInstallationManager()->isUpToDate($doFullCheck)) //Are the latest settings and database changes installed.
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_update'));
        elseif (! $this->getInstallationManager()->isSystemUserExists()) // Does the Database contain a System User....
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_system_user'));

        if (! is_null($response)) {
            if ($event->getRequest()->hasSession()) {
                $event->getRequest()->getSession()->remove('settings');
                $event->getRequest()->getSession()->remove('installation_check');
            }
            self::$installing = true;
            $event->setResponse($response);
        } else {
            if ($event->getRequest()->hasSession() && $doFullCheck) {
                $event->getRequest()->getSession()->set('installation_check', strtotime('+6 Hours'));
            }

        }

        $this->event = $event;
        return ;
    }

    /**
     * @var InstallationManager
     */
    private $installationManager;

    /**
     * InstallSubscriber constructor.
     * @param InstallationManager $installationManager
     */
    public function __construct(InstallationManager $installationManager)
    {
        $this->installationManager = $installationManager;
    }

    /**
     * getInstallationManager
     *
     * @return InstallationManager
     */
    public function getInstallationManager(): InstallationManager
    {
        return $this->installationManager;
    }

    /**
     * exceptionCheck
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function exceptionCheck(GetResponseForExceptionEvent $event)
    {
        if ($this->isInstallingRoute($event))
            return ;

        $ex = $event->getException();

        while (! is_null($ex))
        {
            $ex = $ex->getPrevious();
            if ($ex instanceof TableNotFoundException) {
                $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_start'));
                $event->setResponse($response);
            }
        }
    }

    /**
     * isInstalling
     *
     * @param GetResponseEvent $event
     * @return bool
     */
    private function isInstallingRoute(GetResponseEvent $event) : bool
    {
        if (! $event->isMasterRequest() || in_array($event->getRequest()->get('_route'),
                [
                    // Route that Install
                    'installer_start',
                    'installer_database_settings',
                    'installer_database_create',
                    'load_demonstration_data',
                    'installer_update',
                    'blank',
                    'installer_system_user',
                ]
            )
        ) {
            if ($event->getRequest()->hasSession())
                $event->getRequest()->getSession()->remove('settings');
            self::$installing = true;
            return true;
        }

        if (!$event->isMasterRequest() || in_array($event->getRequest()->get('_route'),
                [
                    // Ignore these routes
                    'section_menu_display',
                ]
            )
        ) return true;

        // Ignore the profiler and wdt
        if (strpos($event->getRequest()->get('_route'), '_') === 0)
            return true;

        return false;
    }
}