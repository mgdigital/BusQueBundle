<?php

namespace MGDigital\BusQue\Bundle\AutoQueue;

use MGDigital\BusQue\CommandBusAdapterInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DecircularizedCommandBusAdapter implements CommandBusAdapterInterface, ContainerAwareInterface
{

    private $serviceId;
    private $container;

    public function __construct(string $serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function handle($command, bool $fromQueue = false)
    {
        $this->getCommandBusAdapter()->handle($command, $fromQueue);
    }

    protected function getCommandBusAdapter(): CommandBusAdapterInterface
    {
        return $this->getContainer()->get($this->serviceId);
    }

    protected function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new \BadMethodCallException('The container has not been set.');
        }
        return $this->container;
    }
}