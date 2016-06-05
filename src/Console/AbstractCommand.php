<?php

namespace MGDigital\BusQueBundle\Console;

use MGDigital\BusQue\Implementation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractCommand extends Command implements ContainerAwareInterface
{

    private $implementationId;
    private $container;

    public function __construct(string $implementationId = 'busque.implementation')
    {
        parent::__construct();
        $this->implementationId = $implementationId;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function getImplementation(): Implementation
    {
        return $this->getContainer()->get($this->implementationId);
    }

    protected function getContainer(): ContainerInterface
    {
        if (!$this->container instanceof ContainerInterface) {
            throw new \BadMethodCallException('The container has not been set.');
        }
        return $this->container;
    }
}
