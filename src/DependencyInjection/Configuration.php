<?php declare(strict_types=1);

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
            ->end()
        ;

        return $treeBuilder;
    }
}
