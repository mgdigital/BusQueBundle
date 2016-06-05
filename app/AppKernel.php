<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel {

    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new League\Tactician\Bundle\TacticianBundle(),
            new MGDigital\BusQueBundle\MGDigitalBusQueBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }
}
