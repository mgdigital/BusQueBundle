<?php

namespace MGDigital\BusQueBundle\DependencyInjection;

use MGDigital\BusQue\Implementation;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MGDigitalBusQueExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $impConfig = $config['implementation'];
        $implementation = new Definition(
            Implementation::class,
            [
                new Reference($impConfig['queue_name_resolver']),
                new Reference($impConfig['command_serializer']),
                new Reference($impConfig['command_id_generator']),
                new Reference($impConfig['queue_adapter']),
                new Reference($impConfig['scheduler_adapter']),
                new Reference($impConfig['clock']),
                new Reference($impConfig['commandbus_adapter']),
                new Reference($impConfig['error_handler'])
            ]
        );
        $implementation->setLazy(true);
        $container->setDefinition('busque.implementation', $implementation);
        $container->setAlias('busque.predis_client', $impConfig['predis_client']);
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'busque';
    }
}
