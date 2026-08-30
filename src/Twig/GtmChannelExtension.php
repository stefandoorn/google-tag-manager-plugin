<?php

declare(strict_types=1);

namespace GtmPlugin\Twig;

use GtmPlugin\Resolver\ChannelFeatureResolver;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GtmChannelExtension extends AbstractExtension
{
    public function __construct(
        private readonly ChannelFeatureResolver $featureResolver,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('gtm_channel_allows', $this->gtmChannelAllows(...)),
        ];
    }

    public function gtmChannelAllows(string $feature): bool
    {
        return $this->featureResolver->isEnabled($feature);
    }
}
