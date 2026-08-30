<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\EventListener;

use GtmPlugin\EventListener\ChannelGtmListener;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\Channel;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

final class ChannelGtmListenerTest extends TestCase
{
    public function testListedChannelOverridesIdAndEnablesService(): void
    {
        $channel = new Channel();
        $channel->setCode('B2C');

        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willReturn($channel);

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');

        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            [
                'B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => []],
            ],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);

        $listener->onKernelRequest($event);

        self::assertSame('GTM-B2C', $gtm->getId());
        self::assertTrue($gtm->isEnabled());
    }

    public function testListedChannelWithEnabledFalseDisablesService(): void
    {
        $channel = new Channel();
        $channel->setCode('B2B');

        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willReturn($channel);

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');

        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            [
                'B2B' => ['id' => null, 'enabled' => false, 'features' => []],
            ],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);

        $listener->onKernelRequest($event);

        self::assertFalse($gtm->isEnabled());
        self::assertSame('GTM-GLOBAL', $gtm->getId());
    }

    public function testFeatureOnlyChannelLeavesGlobalGtmStateUntouched(): void
    {
        $channel = new Channel();
        $channel->setCode('B2C');

        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willReturn($channel);

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');
        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            ['B2C' => ['id' => null, 'enabled' => null, 'features' => ['events' => false]]],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);
        $listener->onKernelRequest($event);

        self::assertTrue($gtm->isEnabled());
        self::assertSame('GTM-GLOBAL', $gtm->getId());
    }

    public function testUnlistedChannelLeavesServiceUntouched(): void
    {
        $channel = new Channel();
        $channel->setCode('STAFF');

        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willReturn($channel);

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');

        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            [
                'B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => []],
            ],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);

        $listener->onKernelRequest($event);

        self::assertTrue($gtm->isEnabled());
        self::assertSame('GTM-GLOBAL', $gtm->getId());
    }

    public function testSubRequestIsIgnored(): void
    {
        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->expects(self::never())->method('getChannel');

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');

        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            [
                'B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => []],
            ],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(false);

        $listener->onKernelRequest($event);

        self::assertTrue($gtm->isEnabled());
        self::assertSame('GTM-GLOBAL', $gtm->getId());
    }

    public function testChannelNotFoundLeavesServiceUntouched(): void
    {
        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willThrowException(new ChannelNotFoundException());

        $gtm = new GoogleTagManager(true, 'GTM-GLOBAL');

        $listener = new ChannelGtmListener(
            $gtm,
            $channelContext,
            [
                'B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => []],
            ],
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);

        $listener->onKernelRequest($event);

        self::assertTrue($gtm->isEnabled());
        self::assertSame('GTM-GLOBAL', $gtm->getId());
    }
}
