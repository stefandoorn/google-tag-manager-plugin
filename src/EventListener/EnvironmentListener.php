<?php

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

/**
 * Class EnvironmentListener
 * @package SyliusGoogleTagManagerBundle\EventListener
 */
class EnvironmentListener
{

    /**
     * @var GoogleTagManager
     */
    private $googleTagManager;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * EnvironmentListener constructor.
     * @param bool $enabled
     * @param GoogleTagManagerInterface $googleTagManager
     * @param string $environment
     */
    public function __construct(bool $enabled, GoogleTagManagerInterface $googleTagManager, string $environment)
    {
        $this->googleTagManager = $googleTagManager;
        $this->environment = $environment;
        $this->enabled = $enabled;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$this->enabled) {
            return;
        }

        // Add environment
        $this->googleTagManager->addData('env', $this->environment);
    }
}
