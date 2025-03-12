<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class EnvironmentListener
{
    public function __construct(
        private readonly bool $enabled,
        private readonly GoogleTagManagerInterface $googleTagManager,
        private readonly string $environment,
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
