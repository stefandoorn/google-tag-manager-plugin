<?php

namespace GtmPlugin\EventListener;

use Sylius\Component\Channel\Context\ChannelContextInterface;
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
        $this->googleTagManager->addData('channel', [
            'code' => $this->channelContext->getChannel()->getCode(),
            'name' => $this->channelContext->getChannel()->getName(),
        ]);

        $this->googleTagManager->addData('locale', $this->localeContext->getLocaleCode());

        $this->googleTagManager->addData('currency', $this->currencyContext->getCurrencyCode());
    }
}
