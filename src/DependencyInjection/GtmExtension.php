<?php

declare(strict_types=1);

namespace GtmPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class GtmExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));

        if ($config['inject'] === true) {
            $loader->load('inject.yml');
        }

        foreach ($config['features'] as $feature => $setting) {
            $parameter = \sprintf('gtm.features.%s', $feature);

            $container->setParameter($parameter, $setting);

            if ($setting === true) {
                $loader->load(\sprintf('features/%s.yml', $feature));
            }
        }
    }
}
