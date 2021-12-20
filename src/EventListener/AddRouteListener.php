<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class AddRouteListener
{
    /** @var GoogleTagManagerInterface */
    private $googleTagManager;

    public function __construct(GoogleTagManagerInterface $googleTagManager)
    {
        $this->googleTagManager = $googleTagManager;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->googleTagManager->setData('route', $event->getRequest()->get('_route'));
    }
}
