<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\EventListener;

use GtmPlugin\EventListener\EnvironmentListener;
use GtmPlugin\Resolver\ChannelFeatureResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Xynnn\GoogleTagManagerBundle\Service\GoogleTagManager;

final class EnvironmentListenerTest extends TestCase
{
    public function testEnvironmentIsAddedToGtmObject(): void
    {
        $gtm = new GoogleTagManager(true, 'id1234');
        $this->expectUserDeprecationMessage('Not passing a ChannelFeatureResolver to EnvironmentListener is deprecated and it will be required in the next major version.');
        $listener = new EnvironmentListener(true, $gtm, 'test_env');
        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $mock->method('isMainRequest')->willReturn(true);
        $listener->onKernelRequest($mock);

        self::assertArrayHasKey('env', $gtm->getData());
        self::assertSame($gtm->getData()['env'], 'test_env');
    }

    public function testResolverDecisionOverridesBoolWhenResolverProvided(): void
    {
        // bool says ON, resolver says OFF (globally). Resolver wins.
        $channelContext = $this->createMock(ChannelContextInterface::class);
        $channelContext->method('getChannel')->willThrowException(new ChannelNotFoundException());
        $resolver = new ChannelFeatureResolver($channelContext, ['environment' => false], []);

        $gtm = new GoogleTagManager(true, 'id1234');
        $listener = new EnvironmentListener(true, $gtm, 'test_env', $resolver);

        $mock = $this->getMockBuilder(RequestEvent::class)->disableOriginalConstructor()->getMock();
        $mock->method('isMainRequest')->willReturn(true);
        $listener->onKernelRequest($mock);

        self::assertArrayNotHasKey('env', $gtm->getData());
    }
}
