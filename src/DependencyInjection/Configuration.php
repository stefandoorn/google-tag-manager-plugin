<?php

declare(strict_types=1);

namespace GtmPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('gtm');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('inject')->defaultTrue()->end()
                ->arrayNode('features')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('environment')->defaultTrue()->end()
                        ->booleanNode('route')->defaultTrue()->end()
                        ->booleanNode('context')->defaultTrue()->end()
                        ->booleanNode('events')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('channels')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                        ->validate()
                            ->ifTrue(static fn (array $v): bool => ($v['id'] ?? null) === null &&
                                ($v['enabled'] ?? null) === null &&
                                ($v['features'] ?? []) === [])
                            ->thenInvalid('Channel entry must define at least one of "id", "enabled" or a "features" override.')
                        ->end()
                        ->children()
                            ->scalarNode('id')->defaultNull()->end()
                            ->booleanNode('enabled')->defaultNull()->end()
                            ->arrayNode('features')
                                ->children()
                                    ->booleanNode('environment')->end()
                                    ->booleanNode('route')->end()
                                    ->booleanNode('context')->end()
                                    ->booleanNode('events')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
