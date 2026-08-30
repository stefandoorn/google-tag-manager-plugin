<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\EventListener;

use GtmPlugin\EventListener\AddRouteListener;
use GtmPlugin\Resolver\ChannelFeatureResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

final class AddRouteListenerTest extends TestCase
{
    public function testAddRouteIsAddedToGtmObject(): void
    {
        $request = new Request(attributes: ['_route' => 'test_route']);

        $gtm = new GoogleTagManager(true, 'id1234');
        $this->expectUserDeprecationMessage('Not passing a ChannelFeatureResolver to AddRouteListener is deprecated and it will be required in the next major version.');
        $listener = new AddRouteListener(true, $gtm);

        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();

        $mock
            ->expects($this->once())
            ->method('isMainRequest')
            ->willReturn(true)
        ;

        $mock
            ->expects($this->once())
            ->method('getRequest')
            ->willReturn($request)
        ;

        $listener->onKernelRequest($mock);

        self::assertArrayHasKey('route', $gtm->getData());
        self::assertSame($gtm->getData()['route'], 'test_route');
    }

    public function testResolverDecisionOverridesBoolWhenResolverProvided(): void
    {
        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willThrowException(new ChannelNotFoundException());
        $resolver = new ChannelFeatureResolver($channelContext, ['route' => false], []);

        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new AddRouteListener(true, $gtm, $resolver);

        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $mock->method('isMainRequest')->willReturn(true);
        $listener->onKernelRequest($mock);

        self::assertArrayNotHasKey('route', $gtm->getData());
    }
}
