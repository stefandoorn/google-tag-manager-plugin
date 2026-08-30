<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\DependencyInjection;

use GtmPlugin\DependencyInjection\GtmExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class GtmExtensionTest extends TestCase
{
    public function testChannelsParameterIsEmptyWhenNotConfigured(): void
    {
        $container = $this->buildContainer([]);

        self::assertSame([], $container->getParameter('gtm.channels'));
    }

    public function testChannelsAreNormalisedWithDefaults(): void
    {
        $container = $this->buildContainer([
            'channels' => [
                'B2C' => ['id' => 'GTM-B2C'],
                'B2B' => ['enabled' => false],
                'STAFF' => ['id' => 'GTM-STAFF', 'enabled' => false],
                'EVENTS_OFF' => ['features' => ['events' => false]],
            ],
        ]);

        self::assertSame([
            'B2C' => ['id' => 'GTM-B2C', 'enabled' => true, 'features' => []],
            'B2B' => ['id' => null, 'enabled' => false, 'features' => []],
            'STAFF' => ['id' => 'GTM-STAFF', 'enabled' => false, 'features' => []],
            'EVENTS_OFF' => ['id' => null, 'enabled' => null, 'features' => ['events' => false]],
        ], $container->getParameter('gtm.channels'));
    }

    public function testChannelListenerIsRegistered(): void
    {
        $container = $this->buildContainer([
            'channels' => ['B2C' => ['id' => 'GTM-B2C']],
        ]);

        self::assertTrue($container->hasDefinition('sylius.google_tag_manager.listener.channels'));
        $definition = $container->getDefinition('sylius.google_tag_manager.listener.channels');
        self::assertSame('GtmPlugin\\EventListener\\ChannelGtmListener', $definition->getClass());
        self::assertTrue($definition->hasTag('kernel.event_listener'));
    }

    public function testFeatureResolverIsRegistered(): void
    {
        $container = $this->buildContainer([]);

        self::assertTrue($container->hasDefinition('sylius.google_tag_manager.resolver.channel_feature'));
        $definition = $container->getDefinition('sylius.google_tag_manager.resolver.channel_feature');
        self::assertSame('GtmPlugin\\Resolver\\ChannelFeatureResolver', $definition->getClass());
    }

    public function testTwigChannelExtensionIsRegistered(): void
    {
        $container = $this->buildContainer([]);

        self::assertTrue($container->hasDefinition('sylius.google_tag_manager.twig.channel'));
        $definition = $container->getDefinition('sylius.google_tag_manager.twig.channel');
        self::assertSame('GtmPlugin\\Twig\\GtmChannelExtension', $definition->getClass());
        self::assertTrue($definition->hasTag('twig.extension'));
    }

    /** @param array<string, mixed> $config */
    private function buildContainer(array $config): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $extension = new GtmExtension();
        $extension->load([$config], $container);

        return $container;
    }
}
