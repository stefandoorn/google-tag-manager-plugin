<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\Service;

use GtmPlugin\Resolver\ChannelFeatureResolver;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Core\Model\Channel;

final class ChannelFeatureResolverTest extends TestCase
{
    public function testReturnsGlobalValueForUnlistedChannel(): void
    {
        $resolver = new ChannelFeatureResolver(
            $this->channelContext('STAFF'),
            ['events' => true],
            ['B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => ['events' => false]]],
        );

        self::assertTrue($resolver->isEnabled('events'));
    }

    public function testReturnsChannelOverrideWhenSet(): void
    {
        $resolver = new ChannelFeatureResolver(
            $this->channelContext('B2B'),
            ['events' => true],
            ['B2B' => ['id' => null, 'enabled' => true, 'features' => ['events' => false]]],
        );

        self::assertFalse($resolver->isEnabled('events'));
    }

    public function testFallsBackToGlobalWhenChannelListedButFeatureNotOverridden(): void
    {
        $resolver = new ChannelFeatureResolver(
            $this->channelContext('B2C'),
            ['route' => true, 'events' => false],
            ['B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => ['events' => true]]],
        );

        self::assertTrue($resolver->isEnabled('route'));
    }

    public function testFallsBackToGlobalWhenChannelCannotBeResolved(): void
    {
        $context = $this->createMock(ChannelContextInterface::class);
        $context->method('getChannel')->willThrowException(new ChannelNotFoundException());

        $resolver = new ChannelFeatureResolver(
            $context,
            ['context' => true],
            ['B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => ['context' => false]]],
        );

        self::assertTrue($resolver->isEnabled('context'));
    }

    private function channelContext(string $code): ChannelContextInterface
    {
        $channel = new Channel();
        $channel->setCode($code);
        $context = $this->createMock(ChannelContextInterface::class);
        $context->method('getChannel')->willReturn($channel);

        return $context;
    }
}
