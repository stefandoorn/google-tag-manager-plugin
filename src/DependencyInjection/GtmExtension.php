<?php

namespace GtmPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class GtmExtension
 * @package GtmPlugin\DependencyInjection
 */
final class GtmExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        foreach ($config['features'] as $feature => $setting) {
            $parameter = sprintf('gtm.features.%s', $feature);

            $container->setParameter($parameter, $setting);

            if ($setting === true) {
                $loader->load(sprintf('features/%s.yml', $feature));
            }
        }
    }
}
