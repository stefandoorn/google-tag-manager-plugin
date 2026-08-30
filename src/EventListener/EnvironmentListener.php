<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use GtmPlugin\Resolver\ChannelFeatureResolver;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class EnvironmentListener
{
    public function __construct(
        private readonly bool $enabled,
        private readonly GoogleTagManagerInterface $googleTagManager,
        private readonly string $environment,
        private readonly ?ChannelFeatureResolver $featureResolver = null,
    ) {
        if ($this->featureResolver === null) {
            trigger_error('Not passing a ChannelFeatureResolver to EnvironmentListener is deprecated and it will be required in the next major version.', \E_USER_DEPRECATED);
        }
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $enabled = $this->featureResolver?->isEnabled('environment') ?? $this->enabled;
        if (!$enabled) {
            return;
        }

        if (!$event->isMainRequest()) {
            return;
        }

        $this->googleTagManager->setData('env', $this->environment);
    }
}
