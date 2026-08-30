<?php

declare(strict_types=1);

namespace Tests\GtmPlugin\Unit\Twig;

use GtmPlugin\Resolver\ChannelFeatureResolver;
use GtmPlugin\Twig\GtmChannelExtension;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Twig\TwigFunction;

final class GtmChannelExtensionTest extends TestCase
{
    public function testExposesGtmChannelAllowsFunction(): void
    {
        $extension = new GtmChannelExtension($this->resolver(true));
        $names = array_map(static fn (TwigFunction $f): string => $f->getName(), $extension->getFunctions());

        self::assertContains('gtm_channel_allows', $names);
    }

    public function testFunctionDelegatesToResolver(): void
    {
        $extension = new GtmChannelExtension($this->resolver(false));

        self::assertFalse($extension->gtmChannelAllows('events'));
    }

    private function resolver(bool $enabled): ChannelFeatureResolver
    {
        $context = $this->createMock(ChannelContextInterface::class);
        $context->method('getChannel')->willThrowException(new ChannelNotFoundException());

        return new ChannelFeatureResolver($context, ['events' => $enabled], []);
    }
}
