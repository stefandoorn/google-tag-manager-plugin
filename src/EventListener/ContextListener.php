<?php

declare(strict_types=1);

namespace GtmPlugin\EventListener;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

final class ContextListener
{
    public function __construct(
        private bool $enabled,
        private GoogleTagManagerInterface $googleTagManager,
        private ChannelContextInterface $channelContext,
        private LocaleContextInterface $localeContext,
        private CurrencyContextInterface $currencyContext,
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
