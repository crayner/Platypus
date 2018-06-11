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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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
     * installationCheck
     *
     * @param GetResponseEvent $event
     */
    public function installationCheck(GetResponseEvent $event)
    {
        self::$installing = false;
        if (! $event->isMasterRequest() || in_array($event->getRequest()->get('_route'),
                [
                    // Route that Install
                    'installer_start',
                    'installer_database_settings',
                    'installer_database_create',
                ]
            )
        ) {
            if ($event->getRequest()->hasSession())
                $event->getRequest()->getSession()->remove('settings');
            self::$installing = true;
            return;
        }

        if (! $event->isMasterRequest() || in_array($event->getRequest()->get('_route'),
                [
                    // Ignore these routes
                    'section_menu_display',
                ]
            )
        ) return;

        // Ignore the profiler and wdt
        if (strpos($event->getRequest()->get('_route'), '_') === 0)
            return;


        // Test for db installation.
        $response = null;

        // Are the database settings correct?
        if (! $this->installationManager->isConnected())
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_start'));
        elseif (! $this->installationManager->hasDatabase()) // Can I connect to the database
            $response = new RedirectResponse($this->getInstallationManager()->getRouter()->generate('installer_database_create', ['demo' => 0]));

        if (! is_null($response)) {
            if ($event->getRequest()->hasSession())
                $event->getRequest()->getSession()->remove('settings');
            self::$installing = true;
            $event->setResponse($response);
        }

        return ;
    }

    /**
     * @var InstallationManager
     */
    private $installationManager;

    /**
     * InstallSubscriber constructor.
     * @param InstallationManager $installationManager
     * @param ContainerInterface $container
     */
    public function __construct(InstallationManager $installationManager)
    {
        $this->installationManager = $installationManager;
    }

    /**
     * @return InstallationManager
     */
    public function getInstallationManager(): InstallationManager
    {
        return $this->installationManager;
    }
}