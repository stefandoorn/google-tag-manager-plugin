<?php

namespace Tests\GtmPlugin\EventListener;

use GtmPlugin\EventListener\ContextListener;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

/**
 * Class ContextListenerTest
 * @package Tests\GtmPlugin\EventListener
 * @covers \GtmPlugin\EventListener\ContextListener
 */
class ContextListenerTest extends TestCase
{

    public function testEnvironmentIsAddedToGtmObject()
    {
        $currencyContext = $this->getMockBuilder(CurrencyContextInterface::class)->getMock();
        $currencyContext->expects($this->once())->method('getCurrencyCode')->willReturn('EUR');

        $localeContext = $this->getMockBuilder(LocaleContextInterface::class)->getMock();
        $localeContext->expects($this->once())->method('getLocaleCode')->willReturn('en_US');

        $channel = new Channel();
        $channel->setCode('channelCode');
        $channel->setName('channelName');
        $channelContext = $this->getMockBuilder(ChannelContextInterface::class)->getMock();
        $channelContext->expects($this->once())->method('getChannel')->willReturn($channel);

        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new ContextListener(
            $gtm,
            $channelContext,
            $localeContext,
            $currencyContext);
        $mock = $this->getMockBuilder(GetResponseEvent::class)->disableOriginalConstructor()->getMock();
        $listener->onKernelRequest($mock);

        $this->assertArrayHasKey('locale', $gtm->getData());
        $this->assertArrayHasKey('channel', $gtm->getData());
        $this->assertArrayHasKey('currency', $gtm->getData());

        $this->assertArrayHasKey('code', $gtm->getData()['channel']);
        $this->assertArrayHasKey('name', $gtm->getData()['channel']);

        $this->assertSame($gtm->getData()['locale'], 'en_US');
        $this->assertSame($gtm->getData()['currency'], 'EUR');
        $this->assertSame($gtm->getData()['channel']['code'], 'channelCode');
        $this->assertSame($gtm->getData()['channel']['name'], 'channelName');
    }
}
