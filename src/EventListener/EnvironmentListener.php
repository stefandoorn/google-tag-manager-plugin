<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class EnvironmentListener
{
    public function __construct(
        private bool $enabled,
        private GoogleTagManagerInterface $googleTagManager,
        private string $environment,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$this->enabled) {
            return;
        }

        if (!$event->isMainRequest()) {
            return;
        }

        $this->googleTagManager->setData('env', $this->environment);
    }
}
