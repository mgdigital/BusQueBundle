<?php

namespace MGDigital\BusQue\Bundle\DependencyInjection;

use MGDigital\BusQue\Implementation;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MGDigitalBusQueExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $impConfig = $config['implementation'];
        unset($config['implementation']);
        foreach ($impConfig as $component => $serviceId) {
            $container->setAlias("busque.{$component}", $serviceId);
        }
        foreach ($config as $section => $params) {
            foreach ($params as $key => $value) {
                $container->setParameter("busque.{$section}.{$key}", $value);
            }
        }
        $whitelist = [$container->getParameter('busque.queues.default')];
        foreach ($container->getParameter('busque.queues.classmap') as $class => $queueName) {
            $whitelist[] = $queueName;
        }
        $container->setParameter('busque.queues.whitelist', $whitelist);
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
