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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InstallListener implements EventSubscriberInterface
{
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
     * installationCheck
     *
     * @param GetResponseEvent $event
     */
    public function installationCheck(GetResponseEvent $event)
    {
dump($event);
        // Test for db installation.
        $response = null;

        // Are the database settings correct?
        if (! $this->installationManager->isConnected())
            $response = new RedirectResponse($this->router->generate('install_build'));

        if (! is_null($response))
            $event->setResponse($response);


        return ;
    }

    /**
     * @var InstallationManager
     */
    private $installationManager;

    /**
     * InstallListener constructor.
     * @param InstallationManager $installationManager
     */
    public function __construct(InstallationManager $installationManager)
    {
        $this->installationManager = $installationManager;
    }

}