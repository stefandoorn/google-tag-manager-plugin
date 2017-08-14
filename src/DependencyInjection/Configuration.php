<?php

namespace GtmPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package GtmPlugin\DependencyInjection
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('gtm');

        $rootNode
            ->children()
                ->arrayNode('features')
                    ->children()
                        ->booleanNode('environment')->defaultTrue()->end()
                        ->booleanNode('route')->defaultTrue()->end()
                        ->booleanNode('context')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
