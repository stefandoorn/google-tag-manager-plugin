<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class ChannelGtmListener
{
    /**
     * @param array<string, array{id: ?string, enabled: ?bool}> $channels
     *                                                          Pre-normalised by GtmExtension::load().
     */
    public function __construct(
        private readonly GoogleTagManagerInterface $googleTagManager,
        private readonly ChannelContextInterface $channelContext,
        private readonly array $channels,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        try {
            $code = $this->channelContext->getChannel()->getCode();
        } catch (ChannelNotFoundException) {
            return;
        }

        $config = $this->channels[$code] ?? null;
        if ($config === null) {
            return;
        }

        if ($config['id'] !== null) {
            $this->googleTagManager->setId($config['id']);
        }

        if ($config['enabled'] !== null) {
            $config['enabled'] ? $this->googleTagManager->enable() : $this->googleTagManager->disable();
        }
    }
}
