<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class EnvironmentListener
{
    private GoogleTagManagerInterface $googleTagManager;

    private string $environment;

    public function __construct(GoogleTagManagerInterface $googleTagManager, string $environment)
    {
        $this->googleTagManager = $googleTagManager;
        $this->environment = $environment;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (method_exists($event, 'isMainRequest')) {
            if (!$event->isMainRequest()) {
                return;
            }
        }

        if (method_exists($event, 'isMasterRequest')) {
            if (!$event->isMasterRequest()) {
                return;
            }
        }

        $this->googleTagManager->setData('env', $this->environment);
    }
}
