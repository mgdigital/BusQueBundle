<?php

namespace MGDigital\BusQue\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('busque');

        $rootNode
            ->children()
                ->arrayNode('implementation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('redis_client')->defaultValue('snc_redis.busque_client')->end()
                        ->scalarNode('command_id_generator')->defaultValue('busque.command_id_generator.md5')->end()
                        ->scalarNode('commandbus')->defaultValue('tactician.commandbus')->end()
                        ->scalarNode('logger')->defaultValue('logger')->end()
                    ->end()
                ->end()
                ->arrayNode('queues')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default')->defaultValue('default')->end()
                        ->variableNode('classmap')->defaultValue([])->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
