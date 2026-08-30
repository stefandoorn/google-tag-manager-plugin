<?php

declare(strict_types=1);

namespace GtmPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class GtmExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        $container->setParameter('gtm.inject', $config['inject']);

        foreach ($config['features'] as $feature => $setting) {
            $parameter = \sprintf('gtm.features.%s', $feature);
            $container->setParameter($parameter, $setting);
        }

        $container->setParameter('gtm.features', $config['features']);
        $container->setParameter('gtm.channels', $this->normaliseChannels($config['channels'] ?? []));

        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $this->prependSyliusTwigHooks($container);
    }

    /**
     * @param array<string, array{id?: ?string, enabled?: ?bool, features?: array<string, bool>}> $channels
     *
     * @return array<string, array{id: ?string, enabled: ?bool, features: array<string, bool>}>
     */
    private function normaliseChannels(array $channels): array
    {
        foreach ($channels as $code => $channel) {
            $channels[$code] = [
                'id' => $channel['id'] ?? null,
                'enabled' => $channel['enabled'] ?? (($channel['id'] ?? null) !== null ? true : null),
                'features' => $channel['features'] ?? [],
            ];
        }

        return $channels;
    }

    protected function prependSyliusTwigHooks(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('sylius_twig_hooks')) {
            return;
        }

        // Set to true not false because SyliusTwigHook
        // will register the hookable as a disabled type instead of a template type
        $container->setParameter('gtm.inject', true);
        $container->setParameter('gtm.features.events', true);

        $container->prependExtensionConfig('sylius_twig_hooks', [
            'hooks' => [
                'sylius_shop.base.head' => [
                    'gtm' => [
                        'template' => '@GtmPlugin/head.html.twig',
                        'enabled' => '%gtm.inject%',
                        'priority' => 100,
                    ],
                ],
                'sylius_shop.base.header' => [
                    'gtm' => [
                        'template' => '@GtmPlugin/body.html.twig',
                        'enabled' => '%gtm.inject%',
                        'priority' => 1000,
                    ],
                ],
                'sylius_shop.base#javascripts' => [
                    'gtm_after_body' => [
                        'template' => '@GtmPlugin/after_body.html.twig',
                        'enabled' => '%gtm.inject%',
                        'priority' => -100,
                    ],
                    'gtm_events' => [
                        'template' => '@GtmPlugin/events_javascript.html.twig',
                        'enabled' => '%gtm.features.events%',
                        'priority' => -100,
                    ],
                ],
            ],
        ]);
    }
}
