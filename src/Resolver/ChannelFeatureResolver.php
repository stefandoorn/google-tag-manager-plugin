<?php

declare(strict_types=1);

namespace GtmPlugin\Resolver;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;

final class ChannelFeatureResolver
{
    /**
     * @param array<string, bool>                                                                 $globalFeatures
     * @param array<string, array{id: ?string, enabled: ?bool, features: array<string, bool>}>    $channels
     */
    public function __construct(
        private readonly ChannelContextInterface $channelContext,
        private readonly array $globalFeatures,
        private readonly array $channels,
    ) {
    }

    public function isEnabled(string $feature): bool
    {
        try {
            $code = $this->channelContext->getChannel()->getCode();

            return $this->channels[$code]['features'][$feature] ?? $this->globalFeatures[$feature] ?? false;
        } catch (ChannelNotFoundException) {
            return $this->globalFeatures[$feature] ?? false;
        }
    }
}
