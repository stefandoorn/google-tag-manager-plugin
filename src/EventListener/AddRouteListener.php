<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class AddRouteListener
{
    public function __construct(
        private readonly bool $enabled,
        private readonly GoogleTagManagerInterface $googleTagManager,
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

        $this->googleTagManager->setData('route', $event->getRequest()->attributes->get('_route'));
    }
}
