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
                        ->scalarNode('queue_resolver')->defaultValue('busque.queue_resolver.assembled')->end()
                        ->scalarNode('command_serializer')->defaultValue('busque.command_serializer.php')->end()
                        ->scalarNode('command_id_generator')->defaultValue('busque.command_id_generator.md5')->end()
                        ->scalarNode('queue_adapter')->defaultValue('busque.queue_adapter.predis')->end()
                        ->scalarNode('predis_client')->defaultValue('snc_redis.busque_client')->end()
                        ->scalarNode('scheduler_adapter')->defaultValue('busque.scheduler_adapter.predis')->end()
                        ->scalarNode('clock')->defaultValue('busque.system_clock')->end()
                        ->scalarNode('base_commandbus_adapter')
                            ->defaultValue('busque.commandbus_adapter.tactician')
                        ->end()
                        ->scalarNode('commandbus_adapter')->defaultValue('busque.commandbus_adapter.tactician')->end()
                        ->scalarNode('error_handler')->defaultValue('busque.logging_error_handler')->end()
                        ->scalarNode('error_logger')->defaultValue('logger')->end()
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
