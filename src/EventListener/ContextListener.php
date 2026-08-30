<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use GtmPlugin\Resolver\ChannelFeatureResolver;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class ContextListener
{
    public function __construct(
        private readonly bool $enabled,
        private readonly GoogleTagManagerInterface $googleTagManager,
        private readonly ChannelContextInterface $channelContext,
        private readonly LocaleContextInterface $localeContext,
        private readonly CurrencyContextInterface $currencyContext,
        private readonly ?ChannelFeatureResolver $featureResolver = null,
    ) {
        if ($this->featureResolver === null) {
            trigger_error('Not passing a ChannelFeatureResolver to ContextListener is deprecated and it will be required in the next major version.', \E_USER_DEPRECATED);
        }
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $enabled = $this->featureResolver?->isEnabled('context') ?? $this->enabled;
        if (!$enabled) {
            return;
        }

        if (!$event->isMainRequest()) {
            return;
        }

        try {
            $channel = $this->channelContext->getChannel();

            $this->googleTagManager->setData('channel', [
                'code' => $channel->getCode(),
                'name' => $channel->getName(),
            ]);

            $this->googleTagManager->setData('locale', $this->localeContext->getLocaleCode());

            $this->googleTagManager->setData('currency', $this->currencyContext->getCurrencyCode());
        } catch (ChannelNotFoundException) {
            // Channel wasn't found, nothing should happen in here
        }
    }
}
