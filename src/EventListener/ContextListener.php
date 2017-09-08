<?php

namespace GtmPlugin\EventListener;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManagerInterface;

/**
 * Class ContextListener
 * @package SyliusGoogleTagManagerBundle\EventListener
 */
class ContextListener
{
    /**
     * @var GoogleTagManagerInterface
     */
    private $googleTagManager;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var CurrencyContextInterface
     */
    private $currencyContext;

    /**
     * ContextListener constructor.
     * @param GoogleTagManagerInterface $googleTagManager
     * @param ChannelContextInterface $channelContext
     * @param LocaleContextInterface $localeContext
     * @param CurrencyContextInterface $currencyContext
     */
    public function __construct(
        GoogleTagManagerInterface $googleTagManager,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        CurrencyContextInterface $currencyContext
    ) {
        $this->googleTagManager = $googleTagManager;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->currencyContext = $currencyContext;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        // Only run on master request
        if (!$event->isMasterRequest()) {
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
        } catch (ChannelNotFoundException $e) {}
    }
}
