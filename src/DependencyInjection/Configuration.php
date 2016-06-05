<?php

namespace MGDigital\BusQueBundle\DependencyInjection;

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
                    ->scalarNode('queue_name_resolver')->defaultValue('busque.queue_name_resolver.classname')->end()
                    ->scalarNode('command_serializer')->defaultValue('busque.command_serializer.php')->end()
                    ->scalarNode('command_id_generator')->defaultValue('busque.command_id_generator.object_hash')->end()
                    ->scalarNode('queue_adapter')->defaultValue('busque.queue_adapter.predis')->end()
                    ->scalarNode('predis_client')->defaultValue('snc_redis.busque_client')->end()
                    ->scalarNode('scheduler_adapter')->defaultValue('busque.scheduler_adapter.predis')->end()
                    ->scalarNode('clock')->defaultValue('busque.system_clock')->end()
                    ->scalarNode('commandbus_adapter')->defaultValue('busque.commandbus_adapter.tactician')->end()
                    ->scalarNode('error_handler')->defaultValue('busque.error_handler')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
